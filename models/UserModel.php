<?php
// Backwards-compatibility shim: wrap the Vietnamese NguoiDungModel
require_once __DIR__ . '/NguoiDungModel.php';

if (!class_exists('UserModel') && class_exists('NguoiDungModel')) {
    class UserModel extends NguoiDungModel {}
}
