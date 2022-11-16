<?php namespace Src\Http\Resources\Link;

use Src\Http\Resources\BaseCollection;

class LinkCollection extends BaseCollection
{
	
	/**
	 * @param array $items
	 * @return array
	 */
	public static function toArray( array $items ) : array
	{
		$list = [];
		foreach($items as $item) {
			$list[] = [
				'id' => $item['id'],
				'full_url' => $item['full_url'],
				'code' => $item['code'],
			];
		}
		return $list;
	}
}