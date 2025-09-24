<?php
    namespace Tjall\Pagebuilder\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\View;
    use stdClass;
    use Tjall\Pagebuilder\Enums\PageTypeEnum;
    use Tjall\Pagebuilder\Support\Block;
    use Tjall\Pagebuilder\Registry;

    class Translation extends Model
    {
        protected $table = 'pagebuilder_translations';

        public $timestamps = false;

        protected $guarded = [];

        protected $casts = [
            'type' => PageTypeEnum::class,
            'pagebuilder' => 'array',
            'pageheader' => 'array',
        ];
    }