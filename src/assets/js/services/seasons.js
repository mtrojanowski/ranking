const getArchiveSeasons = () => {
    return fetch('/api/archive-seasons')
        .then(response => response.json());
};

export { getArchiveSeasons }
