import Car from './Car.js'
import RC from './RC.js'
import Human from './Human.js'
import Wall from './Wall.js'
import Camera from './Camera.js'







//Création des objets du jeu
let rc = new RC();
let human = new Human();
let pandaCar = new Car(220, 100, 180, "panda", "red");
let puntoCar = new Car(320, 620, 90, "punto", "blue");






//Mapping de la télécommande à la voiture
function map(x) {
    rc.mapAccelerate(x, '#rc-up');
    rc.mapDecelerate(x, '#rc-down');
    rc.mapTurnLeft(x, '#rc-left');
    rc.mapTurnRight(x, '#rc-right');
}
map(pandaCar);
map(puntoCar);
map(human);





//Initialisation de la camera (auto-scroll)
let container = document.querySelector('.car-city-container');
let camera = new Camera(container);







//Création de la map

//standard walls
let wall1 = new Wall(1050, 2500, 100, 100);
let wall2 = new Wall(2150, 2300, 100, 100);
let wall3 = new Wall(250, 0, 250, 100);
let wall4 = new Wall(0, 500, 100, 400);
let wall5 = new Wall(500, 100, 400, 100);
let wall6 = new Wall(500, 800, 100, 100);
let wall7 = new Wall(850, 100, 100, 100);
let wall8 = new Wall(700, 350, 100, 100);
let wall9 = new Wall(1200, 0, 100, 100);
let wall10 = new Wall(2100, 400, 400, 100);
let wall21 = new Wall(2050, 2500, 100, 100);
let wall22 = new Wall(2150, 2300, 100, 100);
let wall23 = new Wall(2520, 0, 250, 100);
let wall24 = new Wall(450, 2000, 100, 400);
let wall25 = new Wall(500, 2800, 400, 100);
let wall26 = new Wall(500, 1800, 100, 100);
let wall27 = new Wall(2500, 100, 100, 100);
let wall28 = new Wall(1500, 350, 100, 100);
let wall29 = new Wall(1200, 0, 100, 100);
let wall20 = new Wall(1100, 1000, 400, 100);
let wall31 = new Wall(1050, 25000, 300, 100);
let wall32 = new Wall(2150, 23000, 100, 500);
let wall33 = new Wall(2250, 1500, 250, 350);
// let wall34 = new Wall(300, 1200, 100, 500);
// let wall35 = new Wall(1010, 100, 400, 100);
// let wall36 = new Wall(1010, 2000, 600, 100);
// let wall37 = new Wall(2510, 2000, 100, 400);
// let wall38 = new Wall(2010, 1400, 100, 600);
// let wall39 = new Wall(1010, 1600, 100, 300);
// let wall30 = new Wall(1010, 2000, 500, 100);


//border
let wall11 = new Wall(4000, -80, 3100, 100);
let wall12 = new Wall(-80, 3000, 100, 4100);
let wall13 = new Wall(-80, -80, 3100, 100);
let wall14 = new Wall(-80, -80, 100, 3100);

document.getElementById("reload-button").addEventListener("click", function() {
    location.reload();
});














/***********règles du jeu************/

let swap = human;
let player = human;

let swapBtn = document.querySelector('#rc-swap');
swapBtn.addEventListener('click', function() {
    player = swap;
    if (swap !== human) {
        human.getInto(swap);
    }
});
document.addEventListener("keydown", function(event) {
    if (event.code === "ShiftLeft" || event.code === "ShiftRight") {
        player = swap;
        if (swap !== human) {
            human.getInto(swap);
        }
    }
})

function closeEnough(vehicule) {
    if (human.positionX < (vehicule.positionX + 50) && human.positionX > (vehicule.positionX - 50) && human.positionY < (vehicule.positionY + 50) && human.positionY > (vehicule.positionY - 50)) {
        console.log('Close enough');
        return true;
    }
    else {
        return false;
    }
}

function wallColision(vehicule, wall) {
    if (vehicule.positionX >= wall.left && vehicule.positionX <= wall.right && vehicule.positionY >= wall.top && vehicule.positionY <= wall.bottom) {
        console.log('crash');
        document.querySelector('.end-game').classList.remove('none');
        return true;
    }
    else {
        return false;
    }
}



let physicEngine = setInterval(function() {

    camera.focus(player);
    //container.scrollTo(player.positionX, player.positionY);

    //player.refresh();
    human.refresh();
    puntoCar.refresh();
    pandaCar.refresh();


    if (closeEnough(pandaCar)) {
        swapBtn.classList.remove('none');
        swap = pandaCar;
    }
    else if (closeEnough(puntoCar)) {
        swapBtn.classList.remove('none');
        swap = puntoCar;
    }
    else {
        swapBtn.classList.add('none');
        swap = human;
    }

    //Human.position = Car.position si human est dans uen voiture
    if (player !== human) {
        human.positionX = player.positionX;
        human.positionY = player.positionY
        human.refresh();
        if (player.used === false) {
            player = human;
        }
    }



    wallColision(player, wall1);
    wallColision(player, wall2);
    wallColision(player, wall3);
    wallColision(player, wall4);
    wallColision(player, wall5);
    wallColision(player, wall6);
    wallColision(player, wall7);
    wallColision(player, wall8);
    wallColision(player, wall9);
    wallColision(player, wall10);
    wallColision(player, wall11);
    wallColision(player, wall12);
    wallColision(player, wall13);
    wallColision(player, wall14);
}, 1);
