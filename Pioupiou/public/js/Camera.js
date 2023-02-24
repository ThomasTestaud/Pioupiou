class Camera {

    screenHeight;
    screenWidth;
    centerX;
    centerY;
    container;

    constructor(container) {
        this.screenHeight = window.innerHeight;
        this.screenWidth = window.innerWidth;
        this.centerX = this.screenWidth / 2;
        this.centerY = this.screenHeight / 2;
        this.container = container;
    }

    focus(player) {


        this.container.scrollTo(player.positionX - this.centerX, player.positionY - this.centerY);

        // if (player.positionY > this.centerY + (this.screenHeight / 5)) {
        //     this.scrollDown();
        //     this.centerY += 2;
        // }
        // if (player.positionY < this.centerY - (this.screenHeight / 5)) {
        //     this.scrollUp();
        //     this.centerY -= 2;
        // }
        // if (player.positionX > this.centerX + (this.screenWidth / 5)) {
        //     this.scrollRight();
        //     this.centerX += 2;
        // }
        // if (player.positionX < this.centerX - (this.screenWidth / 5)) {
        //     this.scrollLeft();
        //     this.centerX -= 2;
        // }
    }

    scrollRight() {
        this.container.scrollBy(2, 0);
    }

    scrollLeft() {
        this.container.scrollBy(-2, 0);
    }

    scrollDown() {
        this.container.scrollBy(0, 2);
    }

    scrollUp() {
        this.container.scrollBy(0, -2);
    }


}

export default Camera
