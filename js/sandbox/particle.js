class Particle{
    constructor(type, color, state){
        this.type = type;
        this.color = color;
        this.velocity = 0;
        this.staticFrames = 0;
        this.isStatic = false;
        this.state = state;
    }

    update(grid, i, j){
        // Do nothing by default.
    }

    isLiquid(){
        return this.state === 'liquid';
    }

    isSolid(){
        return this.state === 'solid';
    }

    isGas(){
        return this.state === 'gas';
    }
}