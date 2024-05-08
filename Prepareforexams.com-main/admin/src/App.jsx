import React, { Component } from 'react';
import { Route, Routes } from 'react-router-dom';
import { Flip, ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { AuthContext } from './AuthContextProvider';
import { RequireAuth } from './RequireAuth';
import Loading from './components/Loading';
import { api } from './config';
import Home from './pages/Home';
import NotFound from './pages/NotFound';
import PaymentStatus from './pages/PaymentStatus';
import PricingTable from './pages/PricingTable';
import Question from './pages/Question';
import Signin from './pages/Signin';
import Signup from './pages/Signup';
import SubTopic from './pages/SubTopic';
import Subject from './pages/Subject';
import Subscription from './pages/Subscription';
import Teacherquestion from './pages/Teacherquestion';
import Userprofile from './pages/Userprofile';

export class App extends Component {
  constructor(props) {
    super(props);
    this.state = {
      is_login: false,
      step: '',
      user: {},
      token: '',
      loading: true,
    };
  }

  componentDidMount() {
    const items = JSON.parse(localStorage.getItem('@auth_login'));
    if (items != null) {
      this.get_profile(items.token);
      const vendor = items.vendor_id;
      const step = this.state.step;
    } else {
      this.logout();
    }
  }

  login = (step, user, token) => {
    this.setState({
      is_login: true,
      step: step,
      user: user,
      token: token,
      loading: false,
    });
  };

  get_profile = (token) => {
    fetch(api + 'get_student_profile', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        Authorization: token,
      },
      body: JSON.stringify({}),
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.message === 'Unauthenticated.') {
          this.logout();
        }
        if (!json.status) {
        } else {
          this.login('all', json.data, token);
        }
        return json;
      })
      .catch((error) => {
        console.error(error);
      })
      .finally(() => {});
  };

  logout = () => {
    localStorage.clear();
    this.setState({
      is_login: false,
      loading: false,
      token: '',
      user: {},
      step: '',
    });
  };

  render() {
    return (
      <>
        {this.state.loading ? (
          <Loading />
        ) : (
          <AuthContext.Provider
            value={{
              login: this.login,
              logout: this.logout,
              is_login: this.state.is_login,
              token: this.state.token,
              user: this.state.user,
              get_vendor_profile: this.get_profile,
            }}
          >
            <Routes>
              <Route
                exact
                path="/"
                element={
                  <RequireAuth>
                    <Home />
                  </RequireAuth>
                }
              />
              <Route
                path="pricingtable"
                element={
                  // <RequireAuth>
                  <PricingTable />
                  // </RequireAuth>
                }
              />
              <Route
                path="subject/:id"
                element={
                  <RequireAuth>
                    <Subject />
                  </RequireAuth>
                }
              />
              <Route
                path="subscription"
                element={
                  <RequireAuth>
                    <Subscription />
                  </RequireAuth>
                }
              />
              <Route
                path="/subtopic/:exam/:subject/:board"
                element={
                  <RequireAuth>
                    <SubTopic />
                  </RequireAuth>
                }
              />
              <Route path="/signin" element={<Signin />} />
              <Route path="/signup" element={<Signup />} />
              <Route
                path="/user-profile"
                element={
                  <RequireAuth>
                    <Userprofile />
                  </RequireAuth>
                }
              />

              {this.state.user.type === 'teacher' ? (
                <Route
                  path="/teacherquestion/:exam/:subject/:board/:topic/:subtopic"
                  element={
                    <RequireAuth>
                      <Teacherquestion />
                    </RequireAuth>
                  }
                />
              ) : (
                <Route
                  path="/question/:exam/:subject/:board/:topic/:subtopic"
                  element={
                    <RequireAuth>
                      <Question />
                    </RequireAuth>
                  }
                />
              )}
              <Route
                path="/payment_status/:id"
                element={
                  <RequireAuth>
                    <PaymentStatus />
                  </RequireAuth>
                }
              />

              <Route path="*" element={<NotFound />} />
            </Routes>
          </AuthContext.Provider>
        )}
        <ToastContainer
          position="bottom-left"
          autoClose={2000}
          hideProgressBar={true}
          newestOnTop={false}
          closeOnClick
          rtl={false}
          pauseOnFocusLoss
          draggable
          pauseOnHover={false}
          transition={Flip}
          theme="dark"
        />
      </>
    );
  }
}
export default App;
