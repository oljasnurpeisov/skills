<html>
<body>
<div style="background-color:#eee;background-image:none;background-repeat:repeat;background-position:top left;color:#333;font-family:Helvetica,Arial,sans-serif;line-height:1.25">
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tbody>
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="20" cellspacing="0" width="600">
                    <tbody>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" height="90" width="100%"
                                   style="background-color:#2D9CDB;background-image:none;background-repeat:repeat;background-position:top left">
                                <tbody>
                                <tr>
                                    <td align="center" valign="middle">
                                        <a href="<?php echo e(env('APP_URL')); ?>" style="font-size: 24px;color: #fff;text-decoration: none;"><?php echo e(env('APP_NAME','')); ?></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                   style="background-color:#fff;background-image:none;background-repeat:repeat;background-position:top left">
                                <tbody>
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="40" cellspacing="0" height="0" width="100%">
                                            <tbody>
                                            <tr>
                                                <td align="center" valign="middle">
                                                    <div>
                                                        <p><b>Здравствуйте!</b>
                                                        </p>
                                                        <p>Ваша учетная запись была отклонена
                                                        </p>
                                                        <p><b>Причина отклонения:</b>
                                                        </p>
                                                        <p><?php echo $data['message_text']; ?>

                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <p style="color:#7f7f7f;font-size:12px;padding:20px 0">
                                © <?php echo e(date('Y')); ?> <?php echo e(env('APP_NAME', '')); ?>.
                                Все
                                права сохранены.</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
<?php /**PATH D:\OpenServer\domains\enbek\resources\views/app/pages/page/emails/reject.blade.php ENDPATH**/ ?>