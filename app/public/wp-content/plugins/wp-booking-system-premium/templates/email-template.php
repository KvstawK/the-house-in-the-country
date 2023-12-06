<?php
/**
 * WP Booking System - Email Template
 * 
 * Version: 5.7.9
 * 
 */
?>
<!doctype html>
<html>

    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <?php $this->get_styling(); ?>
    </head>

    <body>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
            <tr>
                <td class="container">
                    <table class="bodywrap" align="center">
                        <tr>
                            <td>
                                <table role="presentation" class="main">
                                    <tr>
                                        <td class="header"></td>
                                    </tr>
                                    
                                    <?php if($this->has_logo()): ?>
                                        <tr>
                                            <td class="logo-row">
                                                <div class="logo">
                                                    <img src="<?php $this->get_logo();?>" alt="Logo">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <tr>
                                        <td class="wrapper">
                                            <?php $this->get_body() ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <?php if($this->has_footer_text()): ?>
                            <tr>
                                <td class="footer-text">
                                    <?php echo $this->get_footer_text(); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>