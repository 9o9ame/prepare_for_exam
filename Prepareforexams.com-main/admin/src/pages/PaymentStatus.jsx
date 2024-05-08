import { AuthContext } from '../AuthContextProvider';
import React, { Component } from 'react';
import { Circles } from 'react-loader-spinner';
import { api } from '../config';
import { useLocation, useNavigate, useParams } from 'react-router-dom';
import payment_success from '../assets/images/payment_success.webp';
import payment_declined from '../assets/images/payment_declined.webp';

export class PaymentStatus extends Component {
  static contextType = AuthContext;
  constructor(props) {
    super(props);
    this.state = {
      loading: true,
      successDiv: false,
      errorDiv: false,
    };
  }
  componentDidMount() {
    this.verifyOrder();
  }

  verifyOrder = () => {
    fetch(api + 'verify_order', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: this.context.token,
      },
      body: JSON.stringify({
        txn_id: this.props.id,
      }),
    })
      .then((response) => response.json())
      .then((json) => {
        console.log(json);
        if (json.status) {
          // this.props.history.push('/dashboard');
          this.setState({ successDiv: true });
        } else {
          // this.props.history.push('/pricing');
          this.setState({ errorDiv: true });
        }
      })
      .catch((error) => {
        console.error(error);
      })
      .finally(() => {
        this.setState({ loading: false });
      });
  };

  render() {
    return (
      <div
        className="container"
        style={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          height: '100vh',
          flexDirection: 'column',
        }}
      >
        {this.state.successDiv && (
          <>
            <img
              src={payment_success}
              alt="payment_success"
              style={{ width: '250px' }}
            />
            <h4
              style={{
                marginTop: '40px',
                textAlign: 'center',
                lineHeight: '1.5',
                display: 'flex',
                flexDirection: 'column',
              }}
            >
              <strong>Payment Successful!</strong>
              <br /> Your payment has been successfully processed.
              <button
                className="text-capitalize  btn btn-outline-primary mt-4"
                onClick={() => {
                  this.props.navigate('/', { replace: true });
                  this.context.get_vendor_profile(this.context.token);
                }}
              >
                Go to Dashboard
              </button>
            </h4>
          </>
        )}
        {this.state.errorDiv && (
          <>
            <img
              src={payment_declined}
              alt="payment_declined"
              style={{ width: '250px' }}
            />
            <h4
              style={{
                marginTop: '40px',
                textAlign: 'center',
                lineHeight: '1.5',
                display: 'flex',
                flexDirection: 'column',
              }}
            >
              <strong>Payment Failed!</strong>
              <br /> Your payment has been declined.
              <button className="text-capitalize  btn btn-outline-primary">
                <a href="/pricing-table">Go to Pricing Table</a>
              </button>
            </h4>
          </>
        )}
        {this.state.loading && (
          <>
            <Circles
              height="80"
              width="80"
              color="#4fa94d"
              ariaLabel="circles-loading"
              wrapperStyle={{}}
              wrapperclassName=""
              visible={true}
            />
            <h4
              style={{
                marginTop: '40px',
                textAlign: 'center',
                lineHeight: '1.5',
              }}
            >
              <strong>Verifying Payment...</strong>
              <br /> Please wait while we verify your payment. You will be
              redirected to your dashboard shortly.
              <br />
              Meanwhile, do not refresh the page or close the browser.
            </h4>
          </>
        )}
      </div>
    );
  }
}

function Navigate(props) {
  const abcd = useNavigate();
  const location = useLocation();
  return (
    <PaymentStatus
      {...props}
      {...useParams()}
      navigate={abcd}
      location={location}
    />
  );
}

export default (props) => <Navigate {...props} />;
