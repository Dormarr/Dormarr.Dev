class SandGrid {
    constructor(width, height, cellSize) {
        this.cellSize = cellSize;
        this.cols = Math.floor(width / cellSize);
        this.rows = Math.floor(height / cellSize);

        this.grid = Array.from({ length: this.cols }, () =>
            Array.from({ length: this.rows }, () => null)
        );

        this.activeCells = new Set();
    }

    inBounds(i, j) {
        return i >= 0 && i < this.cols && j >= 0 && j < this.rows;
    }

    isEmpty(i, j) {
        return this.inBounds(i, j) && this.grid[i][j] === null;
    }

    get(i, j) {
        return this.inBounds(i, j) ? this.grid[i][j] : null;
    }

    set(i, j, particle) {
        if (this.inBounds(i, j)) {
            this.grid[i][j] = particle;
            if (particle) {
                this.activeCells.add(`${i},${j}`);
            } else {
                // this.activeCells.delete(`${i},${j}`);
            }
        }
    }

    move(fromI, fromJ, toI, toJ) {
        const particle = this.get(fromI, fromJ);
        if (!particle || !this.isEmpty(toI, toJ)) return;

        this.grid[toI][toJ] = particle;
        this.grid[fromI][fromJ] = null;

        this.activeCells.add(`${toI},${toJ}`);
        this.activeCells.add(`${fromI},${fromJ}`);

        // Wake up neighbors of destination
        for (let dx = -1; dx <= 1; dx++) {
            for (let dy = -1; dy <= 1; dy++) {
                if (dx === 0 && dy === 0) continue;
                const ni = toI + dx;
                const nj = toJ + dy;
                const neighbor = this.get(ni, nj);
                if (neighbor && neighbor.isStatic) {
                    neighbor.isStatic = false;
                    neighbor.staticFrames = 0;
                    this.activeCells.add(`${ni},${nj}`);
                }
            }
        }
    }

    update(ctx) {
        const nextActive = new Set();

        const keys = Array.from(this.activeCells);
        keys.sort(() => Math.random() - 0.5);

        for (const key of keys) {
            const [i, j] = key.split(",").map(Number);
            const p = this.grid[i][j];

            if (!p || p.isStatic) continue;

            const moved = p.update(this, i, j); // ← expect true/false return

            if (moved) {
                p.staticFrames = 0;
            } else {
                p.staticFrames++;
                if (p.staticFrames >= 320) { // or some threshold
                    p.isStatic = true;
                }
            }
        }

        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);

        for (let i = 0; i < this.cols; i++) {
            for (let j = 0; j < this.rows; j++) {
                const p = this.grid[i][j];
                if (p) {
                    ctx.fillStyle = p.color;
                    ctx.fillRect(i * this.cellSize, j * this.cellSize, this.cellSize, this.cellSize);
                    nextActive.add(`${i},${j}`);
                }
            }
        }

        this.activeCells = nextActive;
    }

}
