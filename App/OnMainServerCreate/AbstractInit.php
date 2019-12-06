<?php
namespace App\OnMainServerCreate;

/**
 * 每一个在 MainServerCreate 的时候 需要创建的对象都应该
 * 继承这个这类，将实现一个init静态方法
 */
abstract class AbstractInit
{
    abstract public static function init() :void;
}