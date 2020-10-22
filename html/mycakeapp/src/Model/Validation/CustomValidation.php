<?php
namespace App\Model\Validation;

use Cake\Validation\Validation;

class CustomValidation extends Validation
{
// 下四桁の文字列を抜き取り、それが指定するファイル形式に当てはまるかを調べる
    public static function isImageFile($file){
        $file = $_FILES['file_name'];
        $ext = substr($file['name'], -4);
        $ext_lower = mb_strtolower($ext);
        if($ext_lower == '.gif' || $ext_lower == '.jpg' || $ext_lower == '.png' || $ext_lower == '.jpeg'){
        } else {
            echo '画像ファイルを選択してください。（Customバリデーションで設定したメッセージ）';
        }
    }
}
