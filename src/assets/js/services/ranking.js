const getRanking = (seasonId, army) => {
    const url = '/api/ranking' + (seasonId === undefined ? '' : '/' + seasonId ) + (army !== undefined && army !== '' ? '?army=' + army : '');

    return fetch(url, { cache: "default" })
        .then(response => response.json());
};

const getIndividualRanking = (playerId, seasonId) => {
    const url = '/api/ranking-individual/' + playerId + (seasonId === undefined ? '' : '?seasonId=' + seasonId );
    return fetch(url)
        .then(response => response.json());
};

export { getRanking, getIndividualRanking }
