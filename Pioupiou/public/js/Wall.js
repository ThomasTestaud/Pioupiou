class Wall {

    positionX;
    positionY;
    height;
    width;
    top;
    right;
    bottom;
    left;

    constructor(positionX, positionY, height, width) {
        this.positionX = positionX;
        this.positionY = positionY;
        this.height = height;
        this.width = width;

        this.top = positionY;
        this.right = positionX + width;
        this.bottom = positionY + height;
        this.left = positionX;

        this.drawWall();
    }

    drawWall() {
        let wallSlot = document.querySelector('.wall-slot');
        wallSlot.innerHTML += `<div class="wall" style="position: absolute; top: ${this.positionY}px;left: ${this.positionX}px;width:${this.width}px;height:${this.height}px;"></div>`;
        console.log(`Draw Wall top: ${this.top} right: ${this.right} bottom: ${this.bottom} left: ${this.left}`);
    }
}


export default Wall;
