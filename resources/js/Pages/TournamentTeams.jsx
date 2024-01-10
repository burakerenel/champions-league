import React from "react";
import Helmet from "react-helmet";
import { usePage } from "@inertiajs/react";
import axios from "axios";

const Index = () => {
    const { teams } = usePage().props;
    const generateFixtures = (e) => {
        e.preventDefault();
        axios
            .post("/generate-fixtures", {})
            .then(function (res) {
                // console.log(res.data);
                window.location = "/generated-fixtures";
            })
            .catch(function (error) {
                throw error;
            });
    };
    return (
        <div className="container-fluid">
            <Helmet title="Tournament Teams" />
            <div className="bg-white p-5 rounded mt-3">
                <h4 className="text-center">Tournament Teams</h4>
                <div className="col-4">
                    <div className="card mt-3">
                        <div className="card-header bg-dark text-white">
                            Team Name
                        </div>
                        <ul className="list-group list-group-flush">
                            {teams.map((team) => {
                                return (
                                    <li
                                        className="list-group-item"
                                        key={team.id}
                                    >
                                        {team.name}
                                    </li>
                                );
                            })}
                        </ul>

                        <div className="card-footer">
                            <button
                                type="button"
                                className="btn btn-info text-white"
                                onClick={generateFixtures}
                            >
                                Generate Fixtures
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Index;
