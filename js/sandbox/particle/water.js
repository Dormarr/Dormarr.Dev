class Water extends Particle {
    constructor() {
        super('water', getColourOffset(220, 88, 52), 'liquid');
    }

    update(grid, i, j) {
        const below = grid.get(i, j + 1);
        const left = grid.get(i - 1, j);
        const right = grid.get(i + 1, j);
        const downLeft = grid.get(i - 1, j + 1);
        const downRight = grid.get(i + 1, j + 1);

        this.velocity = Math.min(this.velocity + 0.2, 4);
        const fallDistance = Math.floor(this.velocity);

        // Try falling straight down
        if(!grid.isEmpty(i, j + 1)){
            grid.get(i, j).velocity = 0;
        }else{
            for (let dy = fallDistance; dy >= 1; dy--) {
                const targetJ = j + dy;
                if (!grid.inBounds(i, targetJ) || !grid.isEmpty(i, targetJ)) continue;
    
                grid.move(i, j, i, targetJ);
                return;
            }
        }

        
        // Try diagonally down-left or down-right
        const directions = [
            { dx: -1, dy: 1, cell: downLeft },
            { dx: 1, dy: 1, cell: downRight }
        ];
        for (const { dx, dy, cell } of directions.sort(() => Math.random() - 0.5)) {
            const ni = i + dx, nj = j + dy;
            if (grid.isEmpty(ni, nj)) {
                grid.move(i, j, ni, nj);
                return;
            }
        }

        // Try flowing sideways on same level (left/right)
        const sideways = [
            { dx: -1, cell: left },
            { dx: 1, cell: right }
        ];
        for (const { dx, cell } of sideways.sort(() => Math.random() - 0.5)) {
            const ni = i + dx;
            if (grid.isEmpty(ni, j)) {
                grid.move(i, j, ni, j);
                return;
            }
        }
    }


}