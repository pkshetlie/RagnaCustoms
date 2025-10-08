import {Controller} from "@hotwired/stimulus";
import $ from "jquery";

export default class extends Controller {
    static values = { id: String };

    connect() {
        const key = "dismiss_notification_" + this.idValue;
        const $strip = $(this.element);

        let dismissed = false;
        try {
            dismissed = !!localStorage.getItem(key);
        } catch (e) {
            console.warn("LocalStorage not accessible:", e);
        }

        // If not dismissed, show and bind dismiss button
        if (!dismissed) {
            $strip.removeClass("d-none")
            $strip.find("[data-action='notification-strip#dismiss']").on("click", (event) => {
                event.preventDefault();
                this.dismiss();
            });
        } else {
            $strip.remove();
        }
    }

    dismiss() {
        const key = "dismiss_notification_" + this.idValue;
        const $strip = $(this.element);

        try {
            localStorage.setItem(key, "1");
        } catch (e) {}
        $strip.remove();
    }
}