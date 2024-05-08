import React, { Component } from "react";
import Header from "../components/Header";
import { Link, useLocation, useParams, useNavigate } from "react-router-dom";
import { api } from "../config";

import no_question from "../assets/images/no_question.webp";
import { AuthContext } from "../AuthContextProvider";
import Loading from "../components/Loading";

export class SubTopic extends Component {
  static contextType = AuthContext;
  constructor(props) {
    super(props);
    this.state = {
      isloading: true,
      liked: false,
      question: true,
      question_id: "",
      question: [],
      single_question: [],
      loading: true,
      fullQuestionView: false,
      pre: [],
    };
  }

  componentDidMount() {
    this.fetchQuestion();
  }

  fullQuestionView = (id) => {
    this.setState({ fullQuestionView: !this.state.fullQuestionView });
  };

  componentDidUpdate(prevProps) {
    if (this.props !== prevProps) {
      this.fetchQuestion();
    }
  }

  fetchQuestion = () => {
    this.setState({ loading: true });
    fetch(api + "fetch_topics", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: this.context.token,
      },
      body: JSON.stringify({
        exam_id: this.props.exam,
        subject_id: this.props.subject,
        board_id: this.props.board,
      }),
    })
      .then((response) => response.json())
      .then((json) => {
        if (!json.status) {
          this.setState({ question: [] });
        } else {
          this.setState({ question: json.data, pre: json.pre });
        }
        return json;
      })
      .catch((error) => {
        console.error(error);
      })
      .finally(() => {
        this.setState({ isloading: false });
      });
  };

  state = {
    lists: [
      { id: 1, subjects: "Algebra" },
      { id: 2, subjects: "Geometry" },
      { id: 3, subjects: "Trigonometry" },
      { id: 4, subjects: "Calculus" },
      { id: 5, subjects: "Statistics" },
      { id: 6, subjects: "Probability" },
      { id: 7, subjects: "Linear Algebra" },
      { id: 8, subjects: "Discrete Math" },
      { id: 9, subjects: "Differential Equations" },
      { id: 10, subjects: "Business Math" },
      { id: 11, subjects: "Advanced Mathematics" },
      { id: 12, subjects: "Number Theory" },
      { id: 13, subjects: "Algebraic Geometry" },
      { id: 14, subjects: "Algebraic Topology" },
      { id: 15, subjects: "Algebraic Number Theory" },
      { id: 16, subjects: "Algebraic Combinatorics" },
      { id: 17, subjects: "Algebraic Coding Theory" },
      { id: 18, subjects: "Algebraic Graph Theory" },
      { id: 19, subjects: "Algebraic Logic" },
      { id: 20, subjects: "Algebraic K-Theory" },
      { id: 21, subjects: "Algebraic Number Theory" },
    ],
  };
  render() {
    return (
      <>
        <Header />
        {this.state.isloading ? (
          <Loading />
        ) : (
          <div className="app-content content">
            <div className="content-overlay" />
            <div className="content-wrapper">
              <div className="content-body">
                <div className="content-header-left col-md-6 col-12 mb-2">
                  <div className="row breadcrumbs-top d-block">
                    <div className="breadcrumb-wrapper col-12">
                      <ol className="breadcrumb  bread-heading">
                        <Link to="/">
                          <li className="breadcrumb-item">
                            <a>Dashboard</a>
                          </li>
                        </Link>
                        <li className="breadcrumb-item active">
                          &nbsp; | Sub Topic
                        </li>
                      </ol>
                    </div>
                  </div>
                </div>
                {this.state.question.length > 0 ? (
                  <section id="clpsAnimation" className="clpsAnimation">
                    <div className="row">
                      <div className="col-md-12">
                        <div classname="card">
                          <div classname="card-content">
                            <div classname="card-body">
                              {this.state.question.map((item, index) => {
                                return (
                                  <div
                                    className="accordion mb-2"
                                    id="accordionExample"
                                  >
                                    <div className="accordion-item">
                                      <h2
                                        className="accordion-header"
                                        id="headingTwo"
                                      >
                                        <button
                                          className="accordion-button collapsed"
                                          type="button"
                                          data-toggle="collapse"
                                          data-target={"#collapseTwo" + index}
                                          aria-expanded="true"
                                          aria-controls="collapseOne"
                                        >
                                          {item.topic}
                                        </button>
                                      </h2>
                                      <div
                                        id={"collapseTwo" + index}
                                        className="accordion-collapse collapse"
                                        aria-labelledby="headingOne"
                                        data-parent="#accordionExample"
                                      >
                                        <div className="accordion-body">
                                          <div className="row">
                                            {/* map the list state in such an order, that after every 10 objects, it moves to another column and also the subjects should be in a ul li format */}
                                            {item.subtopic.map((i, index) => (
                                              <div className="col-md-3">
                                                <ul>
                                                  <li>
                                                    {this.context.user.type ===
                                                    "student" ? (
                                                      <Link
                                                        to={
                                                          "/question/" +
                                                          this.props.exam +
                                                          "/" +
                                                          this.props.subject +
                                                          "/" +
                                                          this.props.board +
                                                          "/" +
                                                          item.topic +
                                                          "/" +
                                                          i.sub_topic
                                                        }
                                                      >
                                                        {i.sub_topic}
                                                      </Link>
                                                    ) : (
                                                      <Link
                                                        to={
                                                          "/teacherquestion/" +
                                                          this.props.exam +
                                                          "/" +
                                                          this.props.subject +
                                                          "/" +
                                                          this.props.board +
                                                          "/" +
                                                          item.topic +
                                                          "/" +
                                                          i.sub_topic
                                                        }
                                                      >
                                                        {i.sub_topic}
                                                      </Link>
                                                    )}
                                                  </li>
                                                </ul>
                                              </div>
                                            ))}
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                );
                              })}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </section>
                ) : (
                  <div
                    className="row"
                    style={{
                      display: "flex",
                      justifyContent: "center",
                      alignItems: "center",
                      flexDirection: "column",
                      height: "80vh",
                      width: "100%",
                    }}
                  >
                    <div className="text-center">
                      <img
                        src={no_question}
                        alt=""
                        style={{
                          width: "300px",
                        }}
                      />
                      <h3>No Question Found</h3>
                    </div>
                  </div>
                )}
              </div>
            </div>
          </div>
        )}
      </>
    );
  }
}

function Navigate(props) {
  const abcd = useNavigate();
  const location = useLocation();
  return (
    <SubTopic {...props} {...useParams()} navigate={abcd} location={location} />
  );
}

export default (props) => <Navigate {...props} />;
