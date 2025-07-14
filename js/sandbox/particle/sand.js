class Sand extends Particle {
    constructor() {
        super('sand', getColourOffset(40, 63, 72));
    }

    update(grid, i, j) {
        this.velocity = Math.min(this.velocity + 0.2, 4);
        const targetJ = j + Math.floor(this.velocity);

        for (let dy = 1; dy <= targetJ - j; dy++) {
            if (grid.isEmpty(i, j + dy)) {
                if (dy === targetJ - j || !grid.isEmpty(i, j + dy + 1)) {
                    grid.move(i, j, i, j + dy);
                    return true;
                }
            } else {
                break;
            }
        }

        // Slide
        const belowJ = j + 1;
        const dirs = [ -1, 1 ];
        for (const dir of dirs.sort(() => Math.random() - 0.5)) {
            const ni = i + dir;
            if (grid.isEmpty(ni, belowJ)) {
                grid.move(i, j, ni, belowJ);
                return true;
            }
        }

        return false; // no movement occurred
    }
}
