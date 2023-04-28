(function ($) {
	$(document).ready(function () {
		var wplp_id = 0;
		$(document).on('DOMNodeInserted', function(event) {
			if ($('.wplp-avada-desc').length > 0) {
				$('.wplp-avada-desc').hide();
				wplp_id = $('#wplp_id').val();
				if ($('#wplp_id').val() !== 0) {
					$('.wplp-avada-desc[data-id="'+ wplp_id +'"]').show();
				}
			}
		})
		$(document).on('change', '.wplp_fusion #wplp_id', function() {
			wplp_id = $(this).val();
			$('.wplp-avada-desc').hide();
			$('.wplp-avada-desc[data-id="'+ wplp_id +'"]').show();
		})
	})
}(jQuery));