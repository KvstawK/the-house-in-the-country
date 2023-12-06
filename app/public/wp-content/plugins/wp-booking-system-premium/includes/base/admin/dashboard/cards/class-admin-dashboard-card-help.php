<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;


/**
 * Dashboard card: Need Help.
 *
 */
class WPBS_Admin_Dashboard_Card_Help extends WPBS_Admin_Dashboard_Card
{

    /**
     * Initialize the card.
     *
     */
    protected function init()
    {

        $this->slug    = 'wpbs_card_need_help';
        $this->name    = __('Need help?', 'wp-booking-system');
        $this->context = 'primary';
    }


    /**
     * Output the card's content.
     *
     */
    public function output()
    {

    ?>

        <svg xmlns="http://www.w3.org/2000/svg" width="64.91" height="58.919" viewBox="0 0 64.91 58.919">
            <g>
                <path d="M32.286,58.919H58.915a6,6,0,0,0,5.995-5.995V26.3c0-5-5.108-6.936-8.625-3.42L53.83,25.33h0L42.563,36.6,32.172,46.986h0l-3.307,3.307c-3.516,3.516-1.58,8.625,3.42,8.625" fill="#74e400" />
                <path d="M44.6,25.716a1,1,0,0,0,1.312-.114l3.166-3.3a1,1,0,0,0-1.441-1.385l-2.56,2.664-.854-.627a1,1,0,1,0-1.184,1.613Zm0,9.053a1,1,0,0,0,1.313-.115l3.166-3.3a1,1,0,1,0-1.443-1.384l-2.559,2.665-.854-.627a1,1,0,1,0-1.184,1.613Zm0,8.051a1,1,0,0,0,1.313-.114l3.166-3.3a1,1,0,1,0-1.443-1.385l-2.559,2.665-.854-.627a1,1,0,1,0-1.184,1.614ZM10.143,26.789a3.422,3.422,0,1,0-3.422-3.422A3.426,3.426,0,0,0,10.143,26.789Zm0-4.844a1.422,1.422,0,1,1-1.422,1.422A1.423,1.423,0,0,1,10.143,21.945Zm8.421,4.844h16.1a3.422,3.422,0,1,0,0-6.844h-16.1a3.422,3.422,0,0,0,0,6.844Zm0-4.844h16.1a1.422,1.422,0,1,1,0,2.844h-16.1a1.422,1.422,0,0,1,0-2.844Zm-8.421,13.79a3.422,3.422,0,1,0-3.422-3.422A3.426,3.426,0,0,0,10.143,35.735Zm0-4.843a1.422,1.422,0,1,1-1.422,1.421A1.422,1.422,0,0,1,10.143,30.892Zm8.421,4.843h18.1a3.422,3.422,0,1,0,0-6.843h-18.1a3.422,3.422,0,0,0,0,6.843Zm0-4.843h18.1a1.422,1.422,0,1,1,0,2.843h-18.1a1.422,1.422,0,0,1,0-2.843ZM10.143,44.681A3.422,3.422,0,1,0,6.721,41.26,3.425,3.425,0,0,0,10.143,44.681Zm0-4.843A1.422,1.422,0,1,1,8.721,41.26,1.423,1.423,0,0,1,10.143,39.838Zm8.421,4.843h10.1a3.422,3.422,0,1,0,0-6.843h-10.1a3.422,3.422,0,0,0,0,6.843Zm0-4.843h10.1a1.422,1.422,0,1,1,0,2.843h-10.1a1.422,1.422,0,0,1,0-2.843ZM51.878,2.854H33.69V1a1,1,0,1,0-2,0V2.854h-6.5V1a1,1,0,0,0-2,0V2.854H5a5.006,5.006,0,0,0-5,5V47.176a5.006,5.006,0,0,0,5,5H51.878a5.006,5.006,0,0,0,5-5V7.854A5.006,5.006,0,0,0,51.878,2.854Zm3,44.322a3,3,0,0,1-3,3H5a3,3,0,0,1-3-3V14.4H54.878Zm0-34.78H2V7.854a3,3,0,0,1,3-3H23.188V6.707a1,1,0,1,0,2,0V4.854h6.5V6.707a1,1,0,1,0,2,0V4.854H51.878a3,3,0,0,1,3,3Z" fill="#3b82ff" />
            </g>
        </svg>

        <div class="wpbs-card-need-help-content">
            <p><?php echo __('Need help setting up WP Booking System or have any questions about the plugin?', 'wp-booking-system'); ?></p>
            <a href="https://www.wpbookingsystem.com/documentation/" target="_blank" class="button button-primary"><?php echo __('Read the documentation', 'wp-booking-system'); ?></a>
            <a href="https://www.wpbookingsystem.com/contact/" target="_blank" class="button button-secondary"><?php echo __('Open a support ticket', 'wp-booking-system'); ?></a>
        </div>

    <?php

    }
}
