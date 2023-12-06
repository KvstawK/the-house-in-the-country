<?php
/**
 * WP Booking System - Invoice Template
 * 
 * Version: 1.0.19
 * 
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Invoice</title>

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        @page {
            margin: 0;
            padding: 0;
        }

        header,
        nav,
        section,
        article,
        aside,
        footer {
            display: block;
        }

        a {
            outline: none;
        }

        img {
            -webkit-user-select: none;
            -moz-user-select: none;
            -o-user-select: none;
            -ms-user-select: none;
            user-select: none;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0;
        }

        body {
            font-size: 13px;
            line-height: 18px;
            color: <?php echo $this->get_text_color(); ?>;
        }

        p {
            padding: 0 0 5px 0;
            margin: 0;
        }

        h1 {
            font-size: 26px;
            line-height: 34px;
            color: <?php echo $this->get_accent_color(); ?>;
            padding: 0;
            margin: 0;
        }

        h2 {
            font-size: 18px;
            line-height: 24px;
            color: <?php echo $this->get_accent_color(); ?>;
            padding: 0 0 10px 0;
            margin: 0;
        }

        h4 {
            font-size: 13px;
            line-height: 22px;
            margin: 0;
        }


        /* Header */
        #header {
            padding: 0px 40px 30px 40px;
        }

        #header:after {
            content: '';
            display: table;
            clear: both;
        }

        #header #logo {
            display: inline-block;
            vertical-align: middle;
            width: 50%;
        }

        #header img {
            display: block;
            max-width: 100%;
            height: auto;
            margin: 0;
        }

        #header table {
            width: 100%;
            padding: 0;
            margin: 0;
            border-collapse: collapse;
        }

        #header table tr td:nth-child(2){
            text-align:right;
        }

        #header span.h2 {
            color: #000;
            font-size: 34px;
            font-weight: bold;
            margin: 0;
            line-height: 1;
        }

        #header span.h3 {
            color: #666;
            font-size: 16px;
            margin: -10px 0 0 0;
            font-weight: bold;
        }

        #header #title {
            display: inline-block;
            vertical-align: middle;
            width: 40%;
            text-align: right;
        }

        /* Top Section */
        #top-section {
            background-color: #f5f5f5;
            padding: 15px 40px;
        }

        #top-section:after {
            content: '';
            display: table;
            clear: both;
        }

        #top-section .col {
            float: left;
            width: 36%;
        }

        #top-section .col.third {
            width: 27.99%;
        }

        #top-section .col .col-inner {
            padding-right: 20px;
        }

        #top-section .col.third .col-inner {
            padding-right: 0;
        }


        /* Content */
        #content {
            padding: 20px 20px;
        }

        #content #main-table {
            border-collapse: collapse;
            width: 100%;
            padding: 0;
            margin: 0;
        }

        #content #main-table th {
            font-size: 18px;
            line-height: 24px;
            color: <?php echo $this->get_accent_color(); ?>;
            border-bottom: 4px solid <?php echo $this->get_accent_color(); ?>;
            text-align: right;
            padding: 10px 10px;
        }

        #content #main-table td {
            text-align: right;
            border-bottom: 1px solid #bebebe;
            padding: 10px 10px;
        }

        #content #main-table td.text-large {
            font-size: 15px;
            line-height: 20px;
            font-weight: bold;
        }

        #content #main-table td.text-highlighted {
            color: <?php echo $this->get_accent_color(); ?>;
        }

        #content #main-table td.text-color {
            color: <?php echo $this->get_accent_color(); ?>;
            border: none;
        }

        #content #main-table td.empty {
            border: none;
        }

        #content #main-table th.col-last,
        #content #main-table td.col-last {
            padding-right: 20px;
        }

        #content #main-table .width-large {
            width: 50%;
        }

        #content #main-table .text-left {
            text-align: left;
            padding-left: 20px;
        }

        #content #main-table .text-center {
            text-align: center;
        }


        /* Payment */
        #footer {
            padding: 20px 40px;
        }

        #footer .col {
            float: left;
            width: 45%;
        }

        #footer .col.second {
            float: right;
        }

        #footer .custom-text {
            clear: both;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- Body Wrapper -->
    <div id="body-wrapper">

        <!-- Header -->
        <div id="header">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <!-- Logo -->
                            <span id="logo" class="<?php echo $this->get_logo_type(); ?>">
                                <?php if ($this->get_logo_type() == 'image') : ?>
                                    <img style="max-height: <?php echo $this->get_logo_image_max_height(); ?>px;" src="<?php echo $this->get_logo_image(); ?>" alt="" />
                                <?php else : ?>
                                    <?php if ($this->get_logo_heading()) : ?>
                                        <span class="h2"><?php echo $this->get_logo_heading(); ?></span>
                                    <?php endif; ?>
                                    <?php if ($this->get_logo_subheading()) : ?>
                                        <br />
                                        <span class="h3"><?php echo $this->get_logo_subheading(); ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </span>
                        </td>
                        <td>
                            <!-- Title -->
                            <span id="title">
                                <h1><?php echo $this->get_string('invoice'); ?> <?php echo $this->get_invoice_number(); ?></h1>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Top Section -->
        <div id="top-section">

            <!-- Column -->
            <div class="col first">
                <div class="col-inner">
                    <h2><?php echo $this->get_string('seller'); ?></h2>
                    <p><?php echo $this->get_seller_details(); ?></p>
                </div>
            </div>

            <!-- Column -->
            <div class="col second">
                <div class="col-inner">
                    <h2><?php echo $this->get_string('buyer'); ?></h2>
                    <p><?php echo $this->get_buyer_details(); ?></p>
                </div>
            </div>

            <!-- Column -->
            <div class="col third">
                <div class="col-inner">
                    <h2><?php echo $this->get_string('details'); ?></h2>

                    <h4><?php echo $this->get_string('invoice_number'); ?></h4>
                    <p><?php echo $this->get_invoice_number(); ?></p>

                    <h4><?php echo $this->get_string('invoice_date'); ?></h4>
                    <p><?php echo $this->get_date(); ?></p>

                    <?php if ($this->get_due_date()) : ?>
                        <h4><?php echo $this->get_string('due_date'); ?></h4>
                        <p><?php echo $this->get_due_date(); ?></p>
                    <?php endif; ?>
                </div>
            </div>

        </div>


        <!-- Content -->
        <div id="content">

            <!-- Main Table -->
            <table id="main-table">
                <thead>
                    <tr>
                        <?php foreach ($this->get_table_heading() as $row) : ?>
                            <th class="<?php echo $row['class']; ?>"><?php echo $row['label']; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get_table_line_items() as $rows) : ?>
                        <tr>
                            <?php foreach ($rows as $row) : ?>
                                <td class="<?php echo $row['class']; ?>"><?php echo $row['label']; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <?php foreach ($this->get_table_footer() as $rows) : ?>
                        <tr>
                            <?php foreach ($rows as $row) : ?>
                                <td colspan="<?php echo $row['colspan']; ?>" class="<?php echo $row['class']; ?>"><?php echo $row['label']; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tfoot>
            </table>

        </div>


        <!-- Payment -->
        <div id="footer">

            <?php if ($this->get_footer_notes_body()) : ?>
                <!-- Col -->
                <div class="col first">
                    <h2><?php echo $this->get_footer_notes_heading(); ?></h2>
                    <p><?php echo $this->get_footer_notes_body(); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($this->get_footer_booking_details()) : ?>
                <!-- Col -->
                <div class="col <?php echo !$this->get_footer_notes_body() ? 'first' : 'second'; ?>">
                    <h2><?php echo $this->get_string('booking_details') ?></h2>
                    <p>
                        <?php foreach ($this->get_footer_booking_details() as $row) : ?>
                            <strong><?php echo $row['label'] ?></strong>: <?php echo $row['value'] ?><br />
                        <?php endforeach; ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($this->get_footer_custom_text()) : ?>
                <div class="custom-text">
                    <?php echo $this->get_footer_custom_text(); ?>
                </div>
            <?php endif; ?>

        </div>

    </div>

</body>

</html>