<?php namespace Src\Http\Resources;

abstract class BaseCollection
{
	public static abstract function toArray(array $items) : array;
}