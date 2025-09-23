<?php
    namespace Tjall\Pagebuilder\Models;

    use Illuminate\Database\Eloquent\Model;
    use Tjall\Pagebuilder\Enums\PageTypeEnum;

    class Page extends Model
    {
        protected $table = 'pagebuilder_pages';

        protected $guarded = [];

        protected $casts = [
            'type' => PageTypeEnum::class,
        ];
    }