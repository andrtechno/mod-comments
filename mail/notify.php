<?php

use panix\engine\Html;
use yii\helpers\Url;

/**
 * @var \panix\mod\comments\models\Comments $comment
 */

$thStyle = 'border-color:#D8D8D8; border-width:1px; border-style:solid;';
?>

    <strong><?= Html::encode($comment->user_name); ?></strong>
<?php
if (!$comment->user_id) {
    echo Html::tag('span', '(гость)', ['class' => 'badge badge-light']);
}
?> Ответил на ваш комментарий:<br/>
    ===============================
    <p><?php echo $comment->text; ?></p>
    ===============================
    <br/>
<?php

if ($comment->handler) {
    echo Html::a('Перейти', Url::to(\yii\helpers\ArrayHelper::merge($comment->handler->getUrl(), ['#' => 'comment-' . $comment->id]), true), ['target' => '_blank']);
}
?>