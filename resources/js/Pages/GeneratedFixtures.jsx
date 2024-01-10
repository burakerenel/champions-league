import React from "react";
import Helmet from "react-helmet";
import { usePage } from "@inertiajs/react";

const Index = () => {
    const { fixtures } = usePage().props;

    const groupedFixtures = fixtures.reduce((acc, fixture) => {
        const week = fixture.week;

        if (!acc[week]) {
            acc[week] = [];
        }

        acc[week].push(fixture);
        return acc;
    }, {});

    const goSimulation = () => {
        window.location = "/simulation";
    };

    return (
        <div className="container-fluid">
            <Helmet title="Generated Fixtures" />
            <div className="bg-white p-5 rounded mt-3">
                <h4 className="text-center">Generated Fixtures</h4>

                <div className="row">
                    {Object.keys(groupedFixtures).map((week) => (
                        <div key={week} className="col-md-4">
                            <div className="card mt-3">
                                <div className="card-header bg-dark text-white">
                                    Week {week}
                                </div>
                                <table className="table table-bordered mb-0">
                                    <tbody>
                                        {groupedFixtures[week].map(
                                            (fixture, index) => (
                                                <tr key={index}>
                                                    <td className="w-50">
                                                        {fixture.team1.name}
                                                    </td>
                                                    <td className="w-50">
                                                        {fixture.team2.name}
                                                    </td>
                                                </tr>
                                            )
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    ))}
                </div>

                <div className="col-12 mt-3">
                    <button
                        type="button"
                        className="btn btn-info text-white"
                        onClick={goSimulation}
                    >
                        Start Simulation
                    </button>
                </div>
            </div>
        </div>
    );
};

export default Index;
