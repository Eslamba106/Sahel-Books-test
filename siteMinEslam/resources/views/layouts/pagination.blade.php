<?php if (isset($pagination) && $pagination['totalPages'] > 1) : ?>
<?php
$c_url = $_SERVER['REQUEST_URI'];
$url = preg_replace('/&page=(\d+)/', '', $c_url);
$url = preg_replace('/\?page=(\d+)/', '', $url);
// $arr = explode("/",URL::current());
// // echo '<pre>';
// // print_r($arr);
// // echo '</pre>';
// $siteName = $arr[3];
// // echo $siteName . '<br>';
// $one = str_replace($siteName, "", URL::current());
// $two = $one . $url;
// print_r ($pagination['currentPage']);
if(strpos($url, '?') > -1) {
    $url = $url;
    $ch = '&';
} else {
    $url = $url . '?';
    $ch = '';
}
$end_url  = $url;
// echo $end_url . '<br>';
$c_page = 1;
if (!empty($_GET['page'])) {
$c_page = $_GET['page'];
} 
?>
<!-- start pagination -->
<div class="col-md-12 text-center mt-50">
    <nav>
        <ul class="pagination">
            <li class="page-item page_prev <?php echo ($c_page == 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo (($c_page - 1) > 0) ? $end_url  . $ch  . 'page=' . 1 : ''; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="page-item page_prev <?php echo ($c_page == 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo (($c_page - 1) > 0) ? $end_url  . $ch  . 'page=' . $c_page - 1 : ''; ?>" aria-label="Previous">
                    <span aria-hidden="true">&lt;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="disabled"></li>
            <?php for ($i = 1; $i <= $pagination['totalPages']; $i++) : ?>
            <?php if ($i >= $c_page - 2 && $i <= $c_page + 2) : ?>
            <?php if ($pagination['currentPage'] == $i) : ?>
            <li class="active page-num">
                <a href="#">
                    <?php echo $i; ?><span class="sr-only"></span>
                </a>
            </li>
            <?php else : ?>
            <li class="page-num">
                <a href="<?php echo $end_url  . $ch  . 'page=' . $i; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
            <?php endif ?>
            <?php endif ?>
            <?php endfor ?>
            <li class="page-item page_next <?php echo ($c_page == $pagination['totalPages']) ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo (($c_page + 1) <= $pagination['totalPages']) ? $end_url  . $ch  . 'page=' . $c_page + 1 : ''; ?>" aria-label="Next">
                    <span aria-hidden="true">&gt;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
            <li class="page-item page_next <?php echo ($c_page == $pagination['totalPages']) ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo (($c_page + 1) <= $pagination['totalPages']) ? $end_url  . $ch  . 'page=' . $pagination['totalPages'] : ''; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<!-- end pagination -->

<?php endif ?>