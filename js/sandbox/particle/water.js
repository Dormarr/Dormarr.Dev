class Water extends Particle {
    constructor() {
        super('water', 'blue');
    }

    update(grid, i, j) {
        this.velocity = Math.min(this.velocity + 0.3, 4);

        for (let dy = 1; dy <= Math.floor(this.velocity); dy++) {
            if (grid.isEmpty(i, j + dy)) {
                grid.move(i, j, i, j + dy);
                return;
            }
        }

        // Try to spread horizontally
        const dirs = [ -1, 1 ];
        for (const dir of dirs.sort(() => Math.random() - 0.5)) {
            const ni = i + dir;
            if (grid.isEmpty(ni, j)) {
                grid.move(i, j, ni, j);
                return;
            }
        }
    }
}
