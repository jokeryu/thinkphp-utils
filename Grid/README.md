## Usage
```
if (IS_AJAX) {
    $grid = new \Org\Util\Grid('Article');
    $grid->formatters['data'] = function($items) {
        return array_map(function($item){
            $item['received'] = date('Y-m-d', $item['received']);
            return $item;
        }, $items);
    };
    $grid->where(array('status'=>array('neq',-1)))->field('id,title,author,keyword,received_time as received')->order('id desc')->makeGridRes();
    exit();
}
```