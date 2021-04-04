const test = $("[is-local]").val() && $("[is-local]").val() == 1 ? true : false;
const site_key = test ? "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" : "6Ld0ZoIaAAAAAPIfk6k-iOmoIqRmuUO0JO-OAxK9";
const secret_key = test ? "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe" : "6Ld0ZoIaAAAAAPZQBQt7kAJxhg_4PyNyNbzC7ZNp";
const geolocation_key = "cd2ee62d1d1747c997a00c4bde447843"

$("[contact-submit]").on("click", function (e) {
    e.preventDefault();
    const form = document.querySelector("[contact-form]");
    const url = form.getAttribute("action");
    const formData = new FormData(form);
    if (!formData.get("plan")) {
        warningMessage("Alert", "You must select a plan")
    } else if (!(formData.get("plan") == "basic"
        || formData.get("plan") == "standard"
        || formData.get("plan") == "premium"
        || formData.get("plan") == "ultra"
        || formData.get("plan") == "desinfection"
        || formData.get("plan") == "nonocuppied")) {
        errorMessage("Error", "This is not a valid plan")
    } else if (!formData.get("residence")) {
        warningMessage("Alert", "You must select a residence")
    } else if (!(formData.get("residence") == "apartment"
        || formData.get("residence") == "house")) {
        errorMessage("Error", "This residence is not valid")
    } else if (!formData.get("full_name")) {
        warningMessage("Alert", "You must write a name")
    } else if (!formData.get("phone")) {
        warningMessage("Alert", "You must write a phone")
    } else if (!formData.get("email") || !isValidEmail(formData.get("email"))) {
        warningMessage("Alert", "You must write a valid email")
    } else if (!formData.get("address")) {
        warningMessage("Alert", "You must write an address")
    } else if (!formData.get("message")) {
        warningMessage("Alert", "Tell us, how can we help you?")
    } else if (formData.get("privacy_policy") != "on") {
        warningMessage("Alert", "You must accept our data treatment policy")
        // }
        //  else if (localStorage.getItem("contact") == "true" || getDays() < 2) {
        // infoMessage("", "Ha pasado muy poco desde tu último mensaje. Danos tiempo para responderte.")
    } else {
        // grecaptcha.ready(function () {
        //     grecaptcha.execute(site_key, { action: 'submit' }).then(function (token) {
        //         // Add your logic to submit to your backend server here.
        //         if (token) {
        getRequest('https://ipgeolocation.abstractapi.com/v1?api_key=' + geolocation_key)
            .done(function (geolocation) {
                formData.set("data", JSON.stringify(geolocation))
                formData.set("ip", geolocation.ip_address)
                // formData.set("token", token)
                postRequest(url, formData).done(function (result) {
                    if (result.success) {
                        successMessage(result.success, result.message)
                        localStorage.setItem("contact", "true")
                        localStorage.setItem("contact_date", Date.now())
                        location.reload()
                    } else {
                        errorMessage(result.success, result.message)
                    }
                })
            });
        //         } else {
        //             errorMessage("", "Lo sentimos, no pudimos verificar tu identidad.")
        //         }
        //     });
        // });

    }
})


function getDays() {
    const time = timeAgo(localStorage.getItem("contact_date"))
    if (!time) {
        return 3;
    } else if (time == "" && time.includes("day")) {
        return time.replace("days").replace("day").trim()
    } else {
        return 0;
    }
}
/**
 * This function returns date on "time ago" format
 */
function timeAgo(date) {
    if (date) {
        date = new Date(date);

        const DATE_UNITS = {
            // month: 2629800,
            day: 86400,
            hour: 3600,
            minute: 60,
            second: 1
        }

        const isMayor = timestamp => timestamp > Date.now()
        const getSecondsDiff = timestamp => (Date.now() - timestamp) / 1000

        const getUnitAndValueDate = (secondsElapsed) => {

            secondsElapsed = Math.abs(secondsElapsed);

            for (const [unit, secondsInUnit] of Object.entries(DATE_UNITS)) {

                if (secondsElapsed >= secondsInUnit || unit === "second") {

                    const mayor = isMayor(date)

                    const value = Math.floor(secondsElapsed / secondsInUnit) * (mayor ? 1 : -1)


                    return { value, unit }
                }

            }

        }

        const rtf = new Intl.RelativeTimeFormat("en")

        const secondsElapsed = getSecondsDiff(date)
        const { value, unit } = getUnitAndValueDate(secondsElapsed)
        return rtf.format(value, unit)
    } else {
        return date
    }
}

