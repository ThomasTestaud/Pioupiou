class Car {

    used = false;
    acceleration = 0.1;
    speed = 0;
    gear = 2;
    positionX = 50;
    positionY = 200;
    direction = 90;
    model;
    color;

    on = false;
    interval;

    constructor(x, y, direction, model, color) {
        this.positionX = x;
        this.positionY = y;
        this.direction = direction;
        this.model = model;
        this.color = color;


        this.refresh();
    }

    use() {
        if (this.used) {
            this.used = false;
            this.onOff();
        }
        else if (this.used === false) {
            this.used = true;
            this.onOff();
        }
    }

    onOff() {
        if (!this.on && this.used) {
            console.log('The car started');
            this.on = true;
            this.engine();
            this.lights();
        }
        else {
            console.log('The car stoped');
            this.gear = 0;
            this.inercia();
            this.lights();
            setTimeout(() => {
                console.log('stop');
                this.on = false;
                clearInterval(this.interval);
            }, 3000);
        }
    }

    accelerate() {
        if (this.used) {
            if (this.on) {
                this.gear += 1;
                console.log('Faster');
                this.inercia();
                console.log(this.speed);
            }
            else {
                console.log('You need to turn on your car before using it!');
            }
        }
    }

    decelerate() {
        if (this.used) {
            if (this.on) {
                console.log('Slower');
                this.gear -= 1;
                this.inercia();
                console.log(this.speed);
            }
            else {
                console.log('You need to turn on your car before using it!');
            }
        }
    }

    turnLeft() {
        if (this.used) {
            if (this.on && this.speed !== 0) {
                console.log('Turn left');
                let i = 0;
                let turn = setInterval(() => {
                    i++;
                    this.direction -= 1;
                    if (i >= 30) {
                        clearInterval(turn);
                    }
                }, 10)
            }
            else {
                console.log('You need to turn on your car before using it!');
            }
        }
    }

    turnRight() {
        if (this.used) {
            if (this.on && this.speed !== 0) {
                console.log('Turn Right');
                let i = 0;
                let turn = setInterval(() => {
                    i++;
                    this.direction += 1;
                    if (i >= 30) {
                        clearInterval(turn);
                    }
                }, 10)
            }
            else {
                console.log('You need to turn on your car before using it!');
            }
        }
    }

    refresh() {
        this.calculateDirection()
        document.querySelector('.' + this.model).style = `top: ${this.positionY}px; left: ${this.positionX}px; transform: rotate(${this.direction}deg); background-color: ${this.color};`;
    }

    lights() {
        console.log('Switch the lights');
        document.querySelectorAll('.headlight-' + this.model)[0].classList.toggle('headlight-on');
        setTimeout(() => {
            document.querySelectorAll('.headlight-' + this.model)[1].classList.toggle('headlight-on');
        }, 200);
        setTimeout(() => {
            document.querySelectorAll('.rearlight-' + this.model)[1].classList.toggle('rearlight-on');
        }, 100);
        setTimeout(() => {
            document.querySelectorAll('.rearlight-' + this.model)[0].classList.toggle('rearlight-on');
        }, 250);
    }

    engine() {
        this.interval = setInterval(() => {
            console.log('engine is running');
            this.positionX += this.Xcoef;
            this.positionY -= this.Ycoef;
            //this.refresh();
        }, 35);
    }

    inercia() {
        if (this.speed <= this.gear) {
            let run = setInterval(() => {
                this.speed += this.acceleration;
                console.log(this.speed);
                if (this.speed >= this.gear) {
                    this.speed = this.gear;
                    console.log(this.speed);
                    console.log('speed === gear');
                    clearInterval(run);
                }
            }, 35)
        }
        else if (this.speed > this.gear) {
            let run = setInterval(() => {
                this.speed -= this.acceleration;
                console.log(this.speed);
                if (this.speed <= this.gear) {
                    this.speed = this.gear;
                    clearInterval(run);
                    console.log(this.speed);
                    console.log('speed === gear');
                }
            }, 35)
        }
    }

    calculateDirection() {
        //calcule la vitesse de déplacement sur chacun des axes en fonction de la rotation de la voiture
        this.Ycoef = this.speed * (Math.cos(this.direction * Math.PI / 180));
        this.Xcoef = this.speed * (Math.sin(this.direction * Math.PI / 180));
    }

    generateHTML() {
        document.querySelector('.car-slot').innerHTML +=
            '<div class="car panda"><div class="headlights"><div class="headlight"></div><div class="headlight"></div></div><div class="pare-brise"><div class="pare-brise-av"></div><div class="pare-brise-ar"></div></div><div class="rearlights"><div class="rearlight"></div><div class="rearlight"></div></div></div>';
    }

    // seatBelt() {
    //     human.positionX = this.positionX;
    //     human.positionY = this.positionY;
    // }
}

export default Car
