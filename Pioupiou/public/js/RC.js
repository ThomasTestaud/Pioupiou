class RC {

    mapAccelerate(x, btn) {
        let input = document.querySelector(btn);
        input.addEventListener('click', function() {
            x.accelerate();
        })
        document.addEventListener("keydown", function(event) {
            if (event.code === "ArrowUp") {
                x.accelerate();
                btn.classList.add('clicked');
            }
        })
        document.addEventListener("keyup", function(event) {
            if (event.code === "ArrowUp") {
                btn.classList.remove('clicked');
            }
        })
    }

    mapDecelerate(x, btn) {
        let input = document.querySelector(btn);
        input.addEventListener('click', function() {
            x.decelerate();
        })
        document.addEventListener("keydown", function(event) {
            if (event.code === "ArrowDown") {
                x.decelerate();
            }
        })
    }

    mapTurnLeft(x, btn) {
        let input = document.querySelector(btn);
        input.addEventListener('click', function() {
            x.turnLeft();
        })
        document.addEventListener("keydown", function(event) {
            if (event.code === "ArrowLeft") {
                x.turnLeft();
            }
        })
    }

    mapTurnRight(x, btn) {
        let input = document.querySelector(btn);
        input.addEventListener('click', function() {
            x.turnRight();
        })
        document.addEventListener("keydown", function(event) {
            if (event.code === "ArrowRight") {
                x.turnRight();
            }
        })
    }



}

export default RC
