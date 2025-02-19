$(document).on('click', '.btn-save', function (e) {

	submitForm("#form", function (form_data) {

        const data = convertSerializeFormData(form_data);

        validate.extend(validate.validators.datetime, {
            // The value is guaranteed not to be null or undefined but otherwise it
            // could be anything.
            parse: function (value, options) {
                return +moment.utc(value);
            },
            // Input is a unix timestamp
            format: function (value, options) {
                var format = options.dateOnly ? "YYYY-MM-DD" : "YYYY-MM-DD hh:mm:ss";
                return moment.utc(value).format(format);
            }
        });

        const validator = validate(
            {
                firstname: data.firstname,
                lastname: data.lastname,
                birthdate: data.birthdate,
                mobileNumber: data.mobileNumber
            },
            {
                firstname: {
                    presence: { allowEmpty: false },
                    type: "string"
                },
                lastname: {
                    presence: { allowEmpty: false },
                    type: "string"
                },
                birthdate: {
                    datetime: {
                        dateOnly: true
                    }
                }
            }
        );

        return getValidatorResponse(validator);

    });

});