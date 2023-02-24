class Human {
    positionX = 130;
    positionY = 100;
    used = true;

    constructor() {
        this.refresh();
    }

    refresh() {
        document.querySelector('.human').style = `top: ${this.positionY}px; left: ${this.positionX}px;`;
    }

    //RC Controlled

    use() {
        if (this.used) {
            this.used = false;
        }
        else if (this.used === false) {
            this.used = true;
        }
    }

    accelerate() {
        if (this.used) {
            let i = 0;
            let run = setInterval(() => {
                i++;
                this.positionY -= 1;
                if (i === 20) {
                    clearInterval(run);
                }
                this.refresh();
            }, 20)
        }
    }

    decelerate() {
        if (this.used) {
            let i = 0;
            let run = setInterval(() => {
                i++;
                this.positionY += 1;
                if (i === 20) {
                    clearInterval(run);
                }
                this.refresh();
            }, 20)
        }
    }

    turnLeft() {
        if (this.used) {
            let i = 0;
            let run = setInterval(() => {
                i++;
                this.positionX -= 1;
                if (i === 20) {
                    clearInterval(run);
                }
                this.refresh();
            }, 20)
        }
    }

    turnRight() {
        if (this.used) {
            let i = 0;
            let run = setInterval(() => {
                i++;
                this.positionX += 1;
                if (i === 20) {
                    clearInterval(run);
                }
                this.refresh();
            }, 20)
        }
    }

    getInto(car) {
        if (this.used) {
            this.used = false;
            document.querySelector('.human').classList.add('none');

            car.use();
        }
        else if (this.used === false) {
            this.used = true;
            document.querySelector('.human').classList.remove('none');
            car.use();
        }
    }


}

export default Human
