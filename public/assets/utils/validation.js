var validation = {
    run: function () {
        $('.invalid-feedback').remove();
        var required = $('.required');
        var empty = 0;
        var is_valid = 1;

        $.each(required, function () {
            var value = $(this).val();
            var $this = $(this);

            // Hapus status sebelumnya
            $this.removeClass('is-invalid is-valid');

            if (value === '' || value === null) {
                empty += 1;
                $this.addClass('is-invalid');
                $this.after('<div class="invalid-feedback">* ' + $this.attr('error') + ' harus diisi.</div>');
            } else {
                $this.addClass('is-valid');
            }
        });

        if (empty > 0) {
            is_valid = 0;
        }

        return is_valid;
    },

    runWithElement: function (elm) {
        let data = $(elm);
        let empty = 0;
        let is_valid = 1;

        $.each(data, function () {
            let $container = $(this);
            $container.find('.invalid-feedback').remove();

            let required = $container.find('.required');

            $.each(required, function () {
                let $this = $(this);
                let value = $this.val();

                // Reset state
                $this.removeClass('is-invalid is-valid');

                if (value === '' || value === null) {
                    empty += 1;
                    $this.addClass('is-invalid');
                    $this.after('<div class="invalid-feedback">* ' + $this.attr('error') + ' harus diisi.</div>');
                } else {
                    $this.addClass('is-valid');
                }
            });

            if (empty > 0) {
                is_valid = 0;
            }
        });

        return is_valid;
    }
};
