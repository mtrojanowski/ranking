import fetch from 'node-fetch';
import MissingDataException from "../exceptions/MissingDataException";
import WrongPlayersInTeamException from "../exceptions/WrongPlayersInTeamException";

const getPreviousTournaments = () => {
    return getTournaments(true);
};

const getFutureTournaments = () => {
    return getTournaments(false);
};

const getTournaments = (previous) => {
    return fetch('/api/tournaments?previous=' + previous)
        .then(response => response.json());
};

const getRank = (rank, type) => {
    if (rank === 'local') {
        return 'Lokalny';
    }

    if (rank === 'master') {
        if (type === 'single') {
            return 'Master indywidualny';
        }

        return 'Master drużynowy';
    }

    return '';
};

const checkData = (tournamentData) => {
    if (
        tournamentData.name === ''
        || tournamentData.town === ''
        || tournamentData.venue === ''
        || tournamentData.organiser === ''
        || tournamentData.points === 0
        || tournamentData.rulesUrl === ''
        || tournamentData.date === ''
    )  {
        throw new MissingDataException();
    }

    if (tournamentData.type === 'team' && tournamentData.playersInTeam <= 2) {
        throw new WrongPlayersInTeamException();
    }

    return tournamentData;
};

const prepareData = (tournamentData) => ({
    name: tournamentData.name,
    town: tournamentData.town,
    venue: tournamentData.venue,
    organiser: tournamentData.organiser,
    points: tournamentData.points,
    rulesUrl: tournamentData.rulesUrl,
    date: tournamentData.date,
    rank: tournamentData.rank,
    type: tournamentData.type,
    playersInTeam: tournamentData.type === 'team' ? tournamentData.playersInTeam : (tournamentData.type === 'double' ? 2 : 1)
});

const createTournament = (tournamentData) =>
        Promise.resolve()
            .then(() => checkData(tournamentData))
            .then((tournamentData) => prepareData(tournamentData))
            .then((tournamentData) => fetch(
                '/api/tournaments',
                {
                    'method': 'POST',
                    'body': JSON.stringify(tournamentData),
                    'headers': {
                        'Content-Type': 'application/json'
                    }
                }
            ).then((response) => response.json()));

const getTournament = (tournamentId) =>
     fetch('/api/tournaments/' + tournamentId)
        .then(response => response.json());

export { getFutureTournaments, getPreviousTournaments, getRank, createTournament, getTournament }
