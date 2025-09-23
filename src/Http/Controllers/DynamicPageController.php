<?php
    namespace Tjall\Pagebuilder\Http\Controllers;

    use Tjall\Pagebuilder\Models\Page;

    class DynamicPageController {
        public function show()  {
            $pageId = request()->route('pageId');

            $page = Page::findOrFail($pageId);

            return $page->render();
        }
    }