import {Controller} from '@hotwired/stimulus';
import 'jquery'

export default class extends Controller {
    static targets = ['img', 'background']
    connect() {
        $("[data-toggle='tooltip'],[title]").tooltip();
        $('.circle').each(function(){
            let items =$(this).find('.menuItem');

            for(let i = 0, l = items.length; i < l; i++) {
                items[i].style.left = (50 - 35*Math.cos(-0.5 * Math.PI - 2*(1/l)*i*Math.PI)).toFixed(4) + "%";

                items[i].style.top = (50 + 35*Math.sin(-0.5 * Math.PI - 2*(1/l)*i*Math.PI)).toFixed(4) + "%";
            }
        })
        function updateCountdown() {
            const now = new Date();
            const targetDate = new Date(now.getFullYear(), 2, 1, 0, 0, 0); // March 1st (month is 0-indexed)

            // If March 1st has passed this year, target next year
            if (now > targetDate) {
                targetDate.setFullYear(targetDate.getFullYear() + 1);
            }

            const difference = targetDate - now;

            if (difference <= 0) {
                document.getElementById('countdown-timer').textContent = 'Scores have been reset!';
                return;
            }

            const days = Math.floor(difference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((difference % (1000 * 60)) / 1000);

            document.getElementById('countdown-timer').textContent =
              `${days} days, ${hours} hours, ${minutes} minutes, ${seconds} seconds`;
        }

        // Update immediately and then every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    disconnect() {
    }

    back() {
        // history.back();// Swup instance
        //  return false;
    }
}
