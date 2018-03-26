<?php
/**
 * @var $this \yii\web\View
 * @var $title string
 */

use frontend\extensions\ThemeButtons\ShareButtonAssets;
use yii\helpers\Url;

ShareButtonAssets::register($this);
?>

<a href="#" class="share-listing">
    <i class="fa fa-share"></i>
    <span>Поделиться</span>
</a>
<div class="modal-inner">
    <h2>Поделиться</h2>

    <ul>
        <li>
            <script type="text/javascript">
                document.write(VK.Share.button(false, {type: 'custom', text: '<i class="fa fa-vk"></i>'}));
            </script>
        </li>

        <li>
            <a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(Url::canonical()) ?>">
                <i class="fa fa-facebook"></i>
            </a>
        </li>

        <li>
            <a class="twitter-share-button" target="_blank" href="https://twitter.com/intent/tweet?text=<?= urlencode($title) ?>&url=<?= urlencode(Url::canonical()) ?>">
                <i class="fa fa-twitter"></i>
            </a>
        </li>
    </ul>
</div>