$ = jQuery.noConflict();
$(document).ready(function () {

    var wpbs_signature_pad_width;

    $(".wpbs-signature-pad").each(function () {
        $pad = $(this);
        wpbs_initialize_signature_pad($pad);
    });

    /**
     * Initialize Signature Pad
     * 
     */
    function wpbs_initialize_signature_pad($pad) {
        if (!$(".wpbs-signature-pad").length) {
            return;
        }
        var canvas = $pad.find('canvas').get(0);

        var signaturePad = new SignaturePad(canvas);

        signaturePad.addEventListener("endStroke", () => {
            $pad.addClass('has-signature');
            data = {
                'data': signaturePad.toData(),
                'dataURL': wpbs_crop_signature_canvas($pad.find('canvas').get(0))
            };
            $pad.find('input').val(JSON.stringify(data));
        });

        wpbs_signature_pad_width = $pad.width();

        wpbs_resize_signature_pad($pad);
        return signaturePad;
    }

    /**
     * Reinitialize signature pad on form refresh
     * 
     */
    $(document).on('wpbs_form_updated', function (e, $calendar_wrapper) {
        var $pad = $calendar_wrapper.find('.wpbs-signature-pad');
        signaturePad = wpbs_initialize_signature_pad($pad);
        if ($pad.find('input').val()) {
            $pad.addClass('has-signature');
            data = JSON.parse($pad.find('input').val());
            signaturePad.fromData(data['data']);
        }

    });

    /**
     * Clear Signature
     * 
     */
    $(document).on('click', '.wpbs-clear-signature', function (e) {
        e.preventDefault();
        $pad = $(this).parents('.wpbs-signature-pad');
        $pad.removeClass('has-signature');
        signaturePad = wpbs_initialize_signature_pad($pad);
        signaturePad.clear();
        $pad.find('input').val('');
    })

    /**
     * Reinitialize on screen resize
     */
    $(window).on('resize', function () {

        $(".wpbs-signature-pad").each(function () {
            $pad = $(this);

            if ($pad.width() != wpbs_signature_pad_width) {
                signaturePad = wpbs_initialize_signature_pad($pad);
                signaturePad.clear();
                $pad.removeClass('has-signature');
                $pad.find('input').val('');
                wpbs_signature_pad_width = $pad.width();
            }

        });
    })

});

/**
 * Resize canvas
 * 
 */
function wpbs_resize_signature_pad($pad) {
    var canvas = $pad.find('canvas').get(0);
    canvas.width = $pad.find('canvas').width();
    canvas.height = $pad.find('canvas').height();
}