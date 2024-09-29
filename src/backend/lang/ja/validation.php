<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute を受け入れる必要があります。',
    'accepted_if' => ':other が :value の場合、:attribute を受け入れる必要があります。',
    'active_url' => ':other が :value の場合、:attribute を受け入れる必要があります。',
    'after' => ':attribute は、:date より後の日付でなければなりません。',
    'after_or_equal' => ':attribute は、:date 以降の日付でなければなりません。',
    'alpha' => ':attribute には文字のみを含める必要があります。',
    'alpha_dash' => ':attribute には、文字、数字、ダッシュ、アンダースコアのみを含める必要があります。',
    'alpha_num' => ':attribute には、文字と数字のみを含める必要があります。',
    'array' => ':attribute は配列でなければなりません。',
    'before' => ':attribute は、:date より前の日付でなければなりません。',
    'before_or_equal' => ':attribute は、:date より前または等しい日付でなければなりません。',
    'between' => [
        'array' => ':attribute には、:min から :max までの項目が含まれている必要があります。',
        'file' => ':attribute は、:min から :max キロバイトの間でなければなりません。',
        'numeric' => ':attribute は、:min と :max の間にある必要があります。',
        'string' => ':attribute は、:min から :max 文字の間でなければなりません。',
    ],
    'boolean' => ':attribute フィールドは true または false でなければなりません。',
    'confirmed' => ':attribute の確認が一致しません。',
    'current_password' => 'パスワードが正しくありません。',
    'date' => ':attribute は有効な日付ではありません。',
    'date_equals' => ':attribute は、:date と等しい日付でなければなりません。',
    'date_format' => ':attribute がフォーマット :format と一致しません。',
    'declined' => ':attribute は拒否する必要があります。',
    'declined_if' => ':other が :value の場合、:attribute を拒否する必要があります。',
    'different' => ':attribute と :other は異なる必要があります。',
    'digits' => ':attribute は :digits 桁でなければなりません。',
    'digits_between' => ':attribute は、:min から :max 桁の間でなければなりません。',
    'dimensions' => ':attribute の画像サイズが無効です。',
    'distinct' => ':attribute フィールドの値が重複しています。',
    'email' => ':attribute は有効な電子メール アドレスである必要があります。',
    'ends_with' => ':attribute は、次のいずれかで終わる必要があります: :values。',
    'enum' => '選択された :attribute は無効です。',
    'exists' => '選択された :attribute は無効です。',
    'file' => ':attribute はファイルでなければなりません。',
    'filled' => ':attribute フィールドには値が必要です。',
    'gt' => [
        'array' => ':attribute には、:value よりも多くの項目が必要です。',
        'file' => ':attribute は :value キロバイトより大きくなければなりません。',
        'numeric' => ':attribute は :value より大きくなければなりません。',
        'string' => ':attribute は :value 文字より大きくなければなりません。',
    ],
    'gte' => [
        'array' => ':attribute には :value 項目以上が必要です。',
        'file' => ':attribute は、:value キロバイト以上である必要があります。',
        'numeric' => ':attribute は :value 以上である必要があります。',
        'string' => ':attribute は :value 文字以上である必要があります。',
    ],
    'image' => ':attribute は画像でなければなりません。',
    'in' => '選択された :attribute は無効です。',
    'in_array' => ':attribute フィールドが :other に存在しません。',
    'integer' => ':attribute は整数でなければなりません。',
    'ip' => ':attribute は有効な IP アドレスでなければなりません。',
    'ipv4' => ':attribute は有効な IPv4 アドレスでなければなりません。',
    'ipv6' => ':attribute は有効な IPv6 アドレスでなければなりません。',
    'json' => ':attribute は有効な JSON 文字列である必要があります。',
    'lt' => [
        'array' => ':attribute の項目数は :value 未満でなければなりません。',
        'file' => ':attribute は :value キロバイト未満でなければなりません。',
        'numeric' => ':attribute は :value 未満でなければなりません。',
        'string' => ':attribute は :value 文字未満でなければなりません。',
    ],
    'lte' => [
        'array' => ':attribute には、:value を超えるアイテムを含めることはできません。',
        'file' => ':attribute は、:value キロバイト以下でなければなりません。',
        'numeric' => ':attribute は :value 以下でなければなりません。',
        'string' => ':attribute は、:value 文字以下でなければなりません。',
    ],
    'mac_address' => ':attribute は有効な MAC アドレスでなければなりません。',
    'max' => [
        'array' => ':attribute には :max を超えるアイテムを含めることはできません。',
        'file' => ':attribute は :max キロバイトを超えてはなりません。',
        'numeric' => ':attribute は :max を超えてはなりません。',
        'string' => ':attribute は :max 文字を超えてはなりません。',
    ],
    'mimes' => ':attribute は、タイプ: :values のファイルでなければなりません。',
    'mimetypes' => ':attribute は、タイプ: :values のファイルでなければなりません。',
    'min' => [
        'array' => ':attribute には、少なくとも :min 項目が必要です。',
        'file' => ':attribute は少なくとも :min キロバイトでなければなりません。',
        'numeric' => ':attribute は少なくとも :min である必要があります。',
        'string' => ':attribute は少なくとも :min 文字でなければなりません。',
    ],
    'multiple_of' => ':attribute は、:value の倍数でなければなりません。',
    'not_in' => '選択された :attribute は無効です。',
    'not_regex' => ':attribute 形式が無効です。',
    'numeric' => ':attribute は数値でなければなりません。',
    'password' => 'パスワードが正しくありません。',
    'present' => ':attribute フィールドが存在する必要があります。',
    'prohibited' => ':attribute フィールドは禁止されています。',
    'prohibited_if' => ':other が :value の場合、:attribute フィールドは禁止されています。',
    'prohibited_unless' => ':values に :other がない限り、:attribute フィールドは禁止されています。',
    'prohibits' => ':attribute フィールドは、:other の存在を禁止します。',
    'regex' => ':attribute 形式が無効です。',
    'required' => ':attribute フィールドは必須です。',
    'required_array_keys' => ':attribute フィールドには、:values のエントリが含まれている必要があります。',
    'required_if' => ':other が :value の場合、:attribute フィールドは必須です。',
    'required_unless' => ':values に :other がない限り、:attribute フィールドは必須です。',
    'required_with' => ':values が存在する場合、:attribute フィールドは必須です。',
    'required_with_all' => ':values が存在する場合、:attribute フィールドは必須です。',
    'required_without' => ':values が存在しない場合、:attribute フィールドは必須です。',
    'required_without_all' => ':values が存在しない場合、:attribute フィールドは必須です。',
    'same' => ':attribute と :other は一致する必要があります。',
    'size' => [
        'array' => ':attribute には :size 項目が含まれている必要があります。',
        'file' => ':attribute は :size キロバイトでなければなりません。',
        'numeric' => ':attribute は :size でなければなりません。',
        'string' => ':attribute は :size 文字でなければなりません。',
    ],
    'starts_with' => ':attribute は、次のいずれかで始まる必要があります: :values。',
    'string' => ':attribute は文字列でなければなりません。',
    'strong_password' => ':attribute には以下を含めなければならない： 大文字1文字、特殊文字1文字、8文字以上。',
    'timezone' => ':attribute は有効なタイムゾーンでなければなりません。',
    'unique' => ':attribute は既に取得されています。',
    'uploaded' => ':attribute のアップロードに失敗しました。',
    'url' => ':attribute は有効な URL でなければなりません。',
    'uuid' => ':attribute は有効な UUID でなければなりません。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
