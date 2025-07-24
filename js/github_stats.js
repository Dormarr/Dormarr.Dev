// I need to do the github call and process the data.

fetch('/public/github_stats.php')
  .then(res => {
    if (!res.ok) {
      throw new Error(`Fetch failed: ${res.status}`);
    }
    return res.json();
  })
  .then(data => {
    console.log('GitHub stats:', data);
  })
  .catch(err => {
    console.error('Error fetching GitHub stats:', err);
});




const SHORT_NAMES = {
  'JavaScript': 'JS',
  'TypeScript': 'TS',
  'Python': 'Py',
  'C++': 'C++',
  'C#': 'C#',
  'CSS': 'CSS',
  'HTML': 'HTML',
  'Shell': 'SH',
  'Hack': 'Hack',
  'Java': 'Java',
  'PHP': 'PHP',
};



fetch('../files/data/github_stats.json')
.then(res => res.json())
.then(data => {
    const container = l('github-stats');

    // Total Bytes Header
    const header = document.createElement('div');
    header.textContent = `All my code: ${data.totalBytes.toLocaleString()} bytes`;
    header.style.fontWeight = 'bold';
    header.style.height = '10%';
    header.style.fontSize = '0.8rem';
    header.style.textAlign = 'center';
    container.appendChild(header);
    
    // Bar column container
    const barColumn = document.createElement('div');
    barColumn.style.display = 'flex';
    barColumn.style.flexDirection = 'column';
    barColumn.style.height = '82%';
    barColumn.style.width = '100%';

    const topLanguages = data.languages
    .sort((a, b) => b.bytes - a.bytes)
    .slice(0, 6);

    const codeColours = {
        'JavaScript': '#f7df1e',
        'TypeScript': '#007ACC',
        'Python': '#306998',
        'C++': '#255583',
        'C#': '#68217A',
        'CSS': '#3498db',
        'HTML': '#E34C26',
        'Shell': '#171717',
        'Hack': '#171717',
        'Java': '#ED8B00',
        'PHP': '#777BB3',
    }

    topLanguages.forEach((lang, index) => {
        let percent = (lang.bytes / data.totalBytes) * 100;
        const shortName = SHORT_NAMES[lang.name] || lang.name;

        const barWrapper = document.createElement('div');
        barWrapper.style.width = '100%';
        barWrapper.style.height = '100%';
        barWrapper.style.display = 'flex';
        barWrapper.style.flexDirection = 'row';

        const line = document.createElement('div');
        line.style.width = '100%';
        line.style.height = '1px';
        line.style.backgroundColor = 'black';
        line.style.zIndex = '2';

        if(percent < 60) percent += 10;

        const bar = document.createElement('div');
        bar.style.height = '100%';
        bar.style.width = `${percent}%`;
        bar.style.backgroundColor = codeColours[lang.name] || '#171717';
        bar.style.display = 'flex';
        bar.style.alignItems = 'center';
        bar.style.justifyContent = 'start';
        bar.style.paddingLeft = '4px';
        const light = getContrastColor(codeColours[lang.name]);
        bar.style.color = light == 'light' ? 'white' : 'black';
        bar.style.fontWeight = 'bold';
        bar.style.fontSize = '0.7rem';

        bar.textContent = shortName;

        const bytes = document.createElement('small');
        bytes.textContent = `${lang.bytes} bytes`;
        bytes.style.width = `${100 - percent}%`;
        bytes.style.textAlign = 'right';
        bytes.style.height = '100%';
        bytes.style.alignContent = 'center';
        bytes.style.paddingRight = '4px';

        barColumn.appendChild(line);
        barWrapper.appendChild(bar);
        barWrapper.appendChild(bytes);
        barColumn.appendChild(barWrapper);
    });

    // Timestamp
    const ts = document.createElement('small');
    ts.textContent = `Updated: ${new Date(data.timestamp).toLocaleString()}`;
    ts.style.display = 'block';
    ts.style.height = '8%';
    ts.style.textAlign = 'center';
    ts.style.fontSize = '0.7rem';
    ts.style.color = '#666';
    container.appendChild(ts);

    container.appendChild(barColumn);

  });