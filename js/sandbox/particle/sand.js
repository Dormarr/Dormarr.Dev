class Sand extends Particle {
    constructor() {
        super('sand', getColourOffset(40, 63, 72), 'solid');
    }


    /* =========================================
    Bugs
    - Water pushed up diagonal sand dunes.
    - Larger mounds don't update properly due to update delay.
    - Checkerboard sand under water.

    ========================================= */
    update(grid, i, j) {
        this.velocity = Math.min(this.velocity + 0.2, 4);
        const fallDistance = Math.floor(this.velocity);

        for (let dy = 1; dy <= fallDistance; dy++) {
            const targetJ = j + dy;
            if (!grid.inBounds(i, targetJ) || !grid.isEmpty(i, targetJ)) continue;

            const below = grid.get(i, targetJ);

            // Empty or liquid = sink
            if (!below || below.isLiquid?.() && this.velocity > 0 ) {
                // If it's a liquid, displace it upward
                if (below && below.isLiquid()) {
                    grid.set(i, targetJ, this);
                    grid.set(i, j, below);

                } else {
                    grid.move(i, j, i, targetJ);
                }
                return;
            } else {
                break;
            }
        }

        // Try to slide into diagonal liquid or empty
        const dirs = [ -1, 1 ];
        for (const dx of dirs.sort(() => Math.random() - 0.5)) {
            const ni = i + dx;
            const nj = j + 1;
            if (!grid.inBounds(ni, nj)) continue;

            const diagonal = grid.get(ni, nj);
            if (!diagonal || diagonal.isLiquid?.()) {
                if (diagonal && diagonal.isLiquid()) {
                    grid.set(ni, nj, this);
                    grid.set(i, j, diagonal);
                } else {
                    grid.move(i, j, ni, nj);
                }
                return;
            }
        }
    }

}