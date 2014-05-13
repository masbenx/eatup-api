<?php namespace Allspark\Transformers;

abstract class Transformer {

	/**
	 * Transform a Collection of items
	 *
	 * @param $items
	 * @return array 
	 */
	public function transformCollection( array $items) {
		return array_map([$this, 'transform'], $items);
	}

	public abstract function transform($item);
}