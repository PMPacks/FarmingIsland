<?php

declare(strict_types=1);

namespace muqsit\vanillagenerator\generator;

use InvalidArgumentException;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\BiomeArray;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\PalettedBlockArray;
use pocketmine\world\format\SubChunk;
use pocketmine\world\generator\Generator;
use pocketmine\world\World;
use ReflectionException;
use ReflectionObject;

class OverworldGenerator extends Generator
{
	/** @var \OverworldGenerator */
	private \OverworldGenerator $generator;

	public function __construct(int $seed, string $preset)
	{
		parent::__construct($seed, $preset);

		$enableUHC = false;

		$presets = explode(':', $preset);
		foreach ($presets as $preset) {
			if (empty($preset)) continue;

			$settings = explode(',', $preset);
			if (count($settings) < 2) {
				throw new InvalidArgumentException("World preset must have a key and a value respectively");
			}

			switch ($settings[0]) {
				case "isUHC":
					$enableUHC = (int)$settings[1] === 1;
					break;
				case "environment":
				case "amplification":
					// TODO: These presets are available in mc-generator but they remain inaccessible for now.
			}
		}

		$this->generator = new \OverworldGenerator($seed, $enableUHC);
	}

	public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void
	{
		$chunk = $world->getChunk($chunkX, $chunkZ);

		$biomeData = $chunk->getBiomeIdArray();
		$pelletedEntries = [];

		foreach ($chunk->getSubChunks() as $y => $subChunk) {
			if (!$subChunk->isEmptyFast()) {
				$pelletedEntries[$y] = $subChunk->getBlockLayers()[0];
			} else {
				$newSubChunk = new SubChunk($subChunk->getEmptyBlockId(), [new PalettedBlockArray($subChunk->getEmptyBlockId())], $subChunk->getBlockSkyLightArray(), $subChunk->getBlockLightArray());
				$chunk->setSubChunk($y, $newSubChunk);

				$pelletedEntries[$y] = $newSubChunk->getBlockLayers()[0];
			}
		}

		$biomes = $this->generator->generateChunk($pelletedEntries, $biomeData, World::chunkHash($chunkX, $chunkZ));

		(function () use ($biomes): void {
			/** @noinspection PhpUndefinedFieldInspection */
			/** @phpstan-ignore-next-line */
			$this->biomeIds = new BiomeArray($biomes);
		})->call($chunk);
	}

	/**
	 * @throws ReflectionException
	 */
	public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void
	{
		$r = new ReflectionObject($world);
		$p = $r->getProperty('chunks');
		$p->setAccessible(true);

		$biomeEntries = [];
		$pelletedEntries = [];
		$dirtyEntries = [];

		/**
		 * @var int $hash
		 * @var Chunk $chunkVal
		 */
		foreach ($p->getValue($world) as $hash => $chunkVal) {
			World::getXZ($hash, $x, $z);

			$array = [];

			foreach ($chunkVal->getSubChunks() as $y => $subChunk) {
				if (!$subChunk->isEmptyFast()) {
					$array[$y] = $subChunk->getBlockLayers()[0];
				} else {
					$newSubChunk = new SubChunk($subChunk->getEmptyBlockId(), [new PalettedBlockArray($subChunk->getEmptyBlockId())], $subChunk->getBlockSkyLightArray(), $subChunk->getBlockLightArray());
					$chunkVal->setSubChunk($y, $newSubChunk);

					$array[$y] = $newSubChunk->getBlockLayers()[0];
				}
			}

			$pelletedEntries[$hash] = $array;
			$biomeEntries[$hash] = $chunkVal->getBiomeIdArray();
			$dirtyEntries[$hash] = $chunkVal->isTerrainDirty();
		}

		$this->generator->populateChunk($pelletedEntries, $biomeEntries, $dirtyEntries, World::chunkHash($chunkX, $chunkZ));

		foreach ($dirtyEntries as $hash => $dirtyEntry) {
			World::getXZ($hash, $x, $z);

			if ($dirtyEntry) {
				$c = $world->getChunk($x, $z);

				$c->setTerrainDirtyFlag(Chunk::DIRTY_FLAG_TERRAIN, true);
				$c->setTerrainDirtyFlag(Chunk::DIRTY_FLAG_BIOMES, true);
			}
		}
	}
}