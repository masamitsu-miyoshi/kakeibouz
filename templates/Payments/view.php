<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ReceiptImage $receiptImage
 */
?>
<img src="data:<?= $receiptImage->media_type ?>;base64,<?= base64_encode(stream_get_contents($receiptImage->data)) ?>">
