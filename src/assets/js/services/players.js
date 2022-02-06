const getPlayers = () => {
    return fetch('/api/players')
        .then(response => response.json());
};

export { getPlayers }
