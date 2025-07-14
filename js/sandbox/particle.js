class Particle{
    constructor(type, color){
        this.type = type;
        this.color = color;
        this.velocity = 0;
        this.staticFrames = 0;
        this.isStatic = false;
    }

    update(grid, i, j){
        // Do nothing by default.
    }
}