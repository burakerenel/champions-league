import React from "react";
import Helmet from "react-helmet";
import { usePage } from "@inertiajs/react";
import axios from "axios";

const Index = () => {
    const {
        tournamentScores,
        currentWeek,
        currentWeekFixture,
        champignshipProdictions,
    } = usePage().props;

    const playNextWeek = (e) => {
        e.preventDefault();
        axios
            .post("/play-next-week", {})
            .then(function () {
                window.location = "/simulation";
            })
            .catch(function (error) {
                throw error;
            });
    };

    const playAllWeeks = (e) => {
        e.preventDefault();
        axios
            .post("/play-all-weeks", {})
            .then(function () {
                window.location = "/simulation";
            })
            .catch(function (error) {
                throw error;
            });
    };

    const resetData = (e) => {
        e.preventDefault();
        axios
            .post("/reset-data", {})
            .then(function (res) {
                console.log(res.data);
                window.location = "/";
            })
            .catch(function (error) {
                throw error;
            });
    };

    let sortedProdictions;

    if (currentWeek === 6) {
        sortedProdictions = champignshipProdictions.map((prediction, index) => {
            return index === 0
                ? { ...prediction, prodiction: 100 }
                : { ...prediction, prodiction: 0 };
        });
    } else {
        sortedProdictions = [...champignshipProdictions].sort(
            (a, b) => b.prodiction - a.prodiction
        );
    }

    return (
        <div className="container-fluid">
            <Helmet title="Simulation" />
            <div className="bg-white p-5 rounded mt-3">
                <h4 className="text-center">Simulation</h4>

                <div className="row mt-4 mb-4 border-bottom">
                    <div className="col-md-6">
                        <table className="table table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th>Team Name</th>
                                    <th>P</th>
                                    <th>W</th>
                                    <th>D</th>
                                    <th>L</th>
                                    <th>GD</th>
                                </tr>
                            </thead>
                            <tbody>
                                {tournamentScores.map((score, index) => {
                                    return (
                                        <tr key={`score-${index}`}>
                                            <td>{score.team.name}</td>
                                            <td>{score.plays}</td>
                                            <td>{score.wins}</td>
                                            <td>{score.draws}</td>
                                            <td>{score.losses}</td>
                                            <td>{score.goal_differences}</td>
                                        </tr>
                                    );
                                })}
                            </tbody>
                        </table>
                    </div>
                    <div className="col-md-3">
                        <table className="table table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th colSpan={2}>{`Week ${currentWeek}`}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {currentWeekFixture.map((fixture, index) => {
                                    return (
                                        <tr key={`fixture-${index}`}>
                                            <td className="w-50">
                                                {fixture.team1.name}
                                            </td>
                                            <td className="w-50">
                                                {fixture.team2.name}
                                            </td>
                                        </tr>
                                    );
                                })}
                            </tbody>
                        </table>
                    </div>
                    <div className="col-md-3">
                        <table className="table table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th>Champignship Prodictions</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                {sortedProdictions.map((row, index) => {
                                    return (
                                        <tr key={`prodiction-${index}`}>
                                            <td className="w-75">{row.team}</td>
                                            <td className="w-25">
                                                {row.prodiction.toFixed(2)}
                                            </td>
                                        </tr>
                                    );
                                })}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="d-flex justify-content-around">
                    <button
                        className="btn btn-info text-white"
                        onClick={playAllWeeks}
                    >
                        Play All Weeks
                    </button>
                    <button
                        className="btn btn-info text-white"
                        onClick={playNextWeek}
                    >
                        Pay Next Week
                    </button>
                    <button className="btn btn-danger" onClick={resetData}>
                        Reset Data
                    </button>
                </div>
            </div>
        </div>
    );
};

export default Index;
