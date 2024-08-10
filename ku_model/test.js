$('#zone_id').change(function() {
    var zoneId = $(this).val();
    var $boothId = $('#booth_id');
    var $loader = $('#loader');

    if (zoneId) {
        $loader.show();
        $boothId.addClass('show'); // Add transition effect
        $.ajax({
            type: 'POST',
            url: 'get_booths.php',
            data: { zone_id: zoneId },
            success: function(data) {
                $boothId.html(data);
            },
            error: function() {
                $boothId.html('<option value="">ไม่สามารถโหลดบูธได้</option>');
            },
            complete: function() {
                $loader.hide();
            }
        });
    } else {
        $boothId.html('<option value="">กรุณาเลือกโซนก่อน</option>');
    }
});

$('[data-toggle="tooltip"]').tooltip({
    trigger: 'hover',
    delay: { show: 500, hide: 100 }
});
