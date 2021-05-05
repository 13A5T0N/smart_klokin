import { StatusBar } from "expo-status-bar";
import React, { Component, useState } from "react";
import { useKeepAwake } from "expo-keep-awake";
import {
  Alert,
  StyleSheet,
  Button,
  Text,
  Platform,
  TextInput,
  TouchableHighlight,
  View,
} from "react-native";
import axios from "axios";

const api = axios.create({
  // baseURL: "http://127.0.0.1:8000/api",
  baseURL: "http://d2ac85701943.ngrok.io/smart/api/public/api/",
});

export default class App extends Component {
  // const [modalVisible, setModalVisible] = useState(false);
  constructor(props) {
    super(props);
    this.state = {
      currentTime: null,
      currentDay: null,
      pin: " ",
      modalVisible: false,
    };
    this.daysArray = ["SUN", "MON", "TUES", "WED", "THUR", "FRI", "SAT", "SUN"];
  }

  componentWill() {
    this.getCurrentTime();
  }

  getDate = () => {
    var date = new Date() - 3;
    var month = new Date().getMonth() + 1;

    this.setState({
      setdate: date + ":" + month,
    });
  };

  getCurrentTime = () => {
    let hour = new Date().getHours();
    let minutes = new Date().getMinutes();
    let seconds = new Date().getSeconds();
    let am_pm = "pm";

    if (minutes < 10) {
      minutes = "0" + minutes;
    }

    if (seconds < 10) {
      seconds = "0" + seconds;
    }

    if (hour > 12) {
      hour = hour - 12;
    }

    if (hour == 0) {
      hour = 12;
    }

    if (new Date().getHours() < 12) {
      am_pm = "am";
    }

    var date = new Date().getDate();
    var months = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ];
    var d = new Date();
    var monthName = months[d.getMonth()];
    var year = new Date().getFullYear();
    this.setState({
      currentTime: hour + ":" + minutes + ":" + seconds + " " + am_pm,
    });

    this.daysArray.map((item, key) => {
      if (key == new Date().getDay()) {
        this.setState({
          currentDay:
            item.toUpperCase() + " - " + monthName + " " + date + " , " + year,
        });
      }
    });
  };

  componentWillUnmount() {
    clearInterval(this.timer);
  }

  componentDidMount() {
    this.timer = setInterval(() => {
      this.getCurrentTime();
    }, 1000);
  }

  time_in = async () => {
    var pin = this.state.pin;
    let res = await api
      .post("/login", { pin: pin })
      .then((res) => {
        var state = res.data.return;
        console.log(state);
        if (state == "3") {
          alert("Already Timed In");
        }
        if (state == "1") {
          alert("Time In Succesfull");
        } else {
          alert("Time In Failed");
        }
      })
      .catch((err) => {
        console.log(err);
      });
  };

  time_out = async () => {
    var pin = this.state.pin;
    let res = await api
      .post("/time_out", { pin: pin })
      .then((res) => {
        var state = res.data.return;
        if (state == "1") {
          alert("Time Out Succesfull");
        } else {
          alert("Time Out Failed");
        }
      })
      .catch((err) => {
        console.log(err);
      });
    console.log(res);
  };

  break_in = async () => {
    var pin = this.state.pin;
    let res = await api
      .post("/break_in", { pin: pin })
      .then((res) => {
        var state = res.data.return;
        console.log(state);
        if (state == "0") {
          alert("Incorrect Pin");
        }
        if (state == "2") {
          alert("Break In Succesfull");
        } else {
          alert("Break In Failed");
        }
      })
      .catch((err) => {
        console.log(err);
      });
  };

  break_out = async () => {
    var pin = this.state.pin;
    let res = await api
      .post("/break_out", { pin: pin })
      .then((res) => {
        var state = res.data.return;
        if (state == "0") {
          alert("Incorrect Pin");
        }
        if (state == "2") {
          alert("Break Out Succesfull");
        } else {
          alert("Break Out Failed");
        }
      })
      .catch((err) => {
        console.log(err);
      });
  };

  switch_in = async () => {
    var pin = this.state.pin;
    let res = await api
      .post("switch_in", { pin: pin })
      .then((res) => {
        var state = res.data.return;
        if (state == "0") {
          alert("Incorrect Pin");
        }
        if (state == "3") {
          alert("Switch In Succesfull");
        } else {
          alert("Switch In Failed");
        }
      })
      .catch((err) => {
        console.log(err);
      });
  };

  switch_out = async () => {
    var pin = this.state.pin;
    let res = await api
      .post("switch_out", { pin: pin })
      .then((res) => {
        var state = res.data.return;
        if (state == "0") {
          alert("Incorrect Pin");
        }
        if (state == "3") {
          alert("Switch Out Succesfull");
        } else {
          alert("Switch Out Failed");
        }
      })
      .catch((err) => {
        console.log(err);
      });
  };

  render() {
    return (
      <View style={styles.container}>
        <View style={styles.indicator}>
          <Text style={{fontSize: 20, color: "#FFFFFF" }}>Branch: Ride</Text>
          <Text style={{fontSize: 20, color: "#FFFFFF" }}>Status: online</Text>
        </View>

        <View style={styles.SquareShapeView}>
          <Text style={styles.textView}>{this.state.currentDay}</Text>
          <Text style={styles.TextHour}>{this.state.currentTime}</Text>
          <TextInput
            style={styles.password}
            secureTextEntry={true}
            onChangeText={(pin) => this.setState({ pin })}
          ></TextInput>

          {/* time in & time out  */}
          <View style={styles.fixToText}>
            <View
              style={{
                height: 10,
                borderRadius: 20,
                width: 150,
                marginLeft: 24,
                marginRight: 3,
                marginTop: 5,
              }}
            >
              <Button
                title="Time In"
                color="#3C8DBC"
                onPress={() => this.time_in()}
              />
            </View>
            <View
              style={{
                height: 10,
                width: 150,
                borderRadius: 20,
                marginLeft: 1,
                marginTop: 5,
              }}
            >
              <Button
                title="Time Out"
                color="#3C8DBC"
                onPress={() => this.time_out()}
              />
            </View>
          </View>

          {/* break in & break out  */}
          <View style={styles.fixToText}>
            <View
              style={{ height: 10, width: 150, marginLeft: 25, marginTop: 30 }}
            >
              <Button
                title="Break In"
                color="#F39C12"
                onPress={() => this.break_in()}
              />
            </View>
            <View
              style={{ height: 10, width: 150, marginLeft: 3, marginTop: 30 }}
            >
              <Button
                title="Break out"
                color="#F39C12"
                onPress={() => this.break_out()}
              />
            </View>
          </View>

          {/* switch in & switch out  */}
          <View style={styles.fixToText}>
            <View
              style={{ height: 10, width: 150, marginLeft: 25, marginTop: 30 }}
            >
              <Button
                title="switch in"
                color="#B23EB5"
                onPress={() => this.switch_in()}
              />
            </View>
            <View
              style={{
                height: 10,
                width: 150,
                marginLeft: 3,
                marginTop: 30,
              }}
            >
              <Button
                title="switch out"
                color="#B23EB5"
                onPress={() => this.switch_out()}
              />
            </View>
          </View>
        </View>

        <StatusBar style="auto" />
      </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    paddingTop: Platform.OS === "ios" ? 20 : 0,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#0B1248",
  },
  SquareShapeView: {
    width: 360,
    height: 360,
    backgroundColor: "#213A60",
    borderRadius: 30,
  },
  textView: {
    color: "#FFFFFF",
    fontSize: 20,
    fontWeight: "bold",
    alignItems: "center",
    justifyContent: "center",
    marginLeft: 90,
    marginTop: 20,
  },
  TextHour: {
    color: "#FFFFFF",
    fontSize: 15,
    alignItems: "center",
    justifyContent: "center",
    marginLeft: 130,
    marginTop: 10,
  },
  password: {
    backgroundColor: "#FFFFFF",
    height: 40,
    borderRadius: 5,
    height: 40,
    margin: 12,
    marginTop: 8,
    borderWidth: 1,
    borderRadius: 20,
    textAlign: "center",
  },
  fixToText: {
    flexDirection: "row",
  },
  modalView: {
    margin: 20,
    backgroundColor: "white",
    borderRadius: 20,
    padding: 35,
    alignItems: "center",
    shadowColor: "#000",
    shadowOffset: {
      width: 0,
      height: 2,
    },
    shadowOpacity: 0.25,
    shadowRadius: 3.84,
    elevation: 5,
  },
  centeredView: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    marginTop: 22,
  },

  modalText: {
    marginBottom: 15,
    textAlign: "center",
  },
  indicator: {
    marginBottom: 100,
    marginLeft: -200,
  },
});
