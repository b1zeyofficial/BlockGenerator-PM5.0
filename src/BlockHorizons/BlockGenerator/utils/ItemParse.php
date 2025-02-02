<?php

namespace BlockHorizons\BlockGenerator\utils;

use BlockHorizons\BlockGenerator\Loader as Main;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\LegacyStringToItemParserException;
use pocketmine\item\StringToItemParser;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\utils\TextFormat;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

final class ItemParse {

  public static function loadItem(array $data): Item {
    if(!isset($data["id"])) {
      throw new \RuntimeException("Invalid item, ItemID is unspecified");
    }
    $item = StringToItemParser::getInstance()->parse((string)$data["id"]);
    if($item === null){
      try{
        $item = LegacyStringToItemParser::getInstance()->parse((string)$data["id"]);
      }catch(LegacyStringToItemParserException){}
      if($item === null){
        \GlobalLogger::get()->error("Unable to parse {$data["id"]} to a valid item");
        return VanillaItems::AIR();
      }
    }
    if(($dmg = ($data["damage"] ?? $data["meta"] ?? 0)) !== 0) {
      $item = LegacyStringToItemParser::getInstance()->parse((string)$data["id"].':'.$dmg);
    }
    if($item === null){
      throw new \RuntimeException("Invalid item, cannot parse '{$data['id']}' to a valid item");
    }
    if(($count = $data["count"] ?? 1) != 1) {
      $item->setCount($count);
    }
    if(($cName = $data["customName"] ?? "") !== "") {
      $item->setCustomName(TextFormat::colorize($cName));
    }
    if(($data["hasEnchantGlint"] ?? false) && count($data["enchants"] ?? []) < 1) {
      $item->setNamedTag($item->getNamedTag()->setTag(Item::TAG_ENCH, new ListTag([
        CompoundTag::create()->setShort("id", 0x7fff)->setShort("lvl", 1)
      ])));
    }
    if(!empty(($ench = $data["enchants"] ?? []))) {
      foreach($ench as $enchantID => $level) {
        $item->addEnchantment(self::loadEnchant($enchantID, $level));
      }
    }
    if(!empty(($lore = $data["lore"] ?? []))) {
      $l = [];
      foreach($lore as $loreLine) {
        $l[] = TextFormat::colorize($loreLine);
      }
      $item->setLore($l);
    }
    return $item;
  }

  public static function loadEnchant(string $enchantID, int $level): EnchantmentInstance {
    if(is_numeric($enchantID)) {
      $enchantment = EnchantmentIdMap::getInstance()->fromId((int)$enchantID);
    } else {
      $enchantment = StringToEnchantmentParser::getInstance()->parse($enchantID);
    }
    if($enchantment instanceof Enchantment) {
      return new EnchantmentInstance($enchantment, $level);
    } else {
      throw new \RuntimeException("Invalid / unknown enchantment ID $enchantID");
    }
  }

  public static function stringToItem(string $id, int $count = 1): ?Item {
    try{
      $item = StringToItemParser::getInstance()->parse($id) ?? LegacyStringToItemParser::getInstance()->parse($id);
    }catch(LegacyStringToItemParserException $e){
      Main::getInstance()->getLogger()->notice(KnownTranslationFactory::commands_give_item_notFound($id)->prefix(TextFormat::RED));
      return null;
    }
    $item->setCount($count);
    return $item;
  }
}
