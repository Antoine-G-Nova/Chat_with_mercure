<template>
    <div id="Chat" class="d-flex justify-content-start align-items-start">
        <div>
            <!-- UsersNav -->
            <TheUsersNav
                :users="users"
                :currentUser="currentUser"
                @changeCurrentUser="setCurrentUser"
            ></TheUsersNav>
        </div>
        <!-- /UsersNav -->

        <div class="chat-container">
            <!-- Display ChatMessage -->
            <div class="chat-messages w-100 ml-4 h-100">
                <span v-for="message in messages">
                    <TheMessage
                        v-if="
                            message.from.id == currentUser ||
                            (message.from.id == connectedUser.id &&
                                message.to == currentUser)
                        "
                        :message="message"
                        :currentUser="currentUser"
                        :connectedUser="connectedUser"
                    ></TheMessage>
                </span>
            </div>
            <!-- /Display ChatMessage -->

            <!-- Edit Message -->
            <form @submit="submitMessage" class="edit">
                <div class="input-group">
                    <input
                        type="text"
                        v-model="messageEnCours"
                        class="form-control"
                        placeholder="Say something"
                    />
                    <div class="input-group-append">
                        <button class="btn btn-success" type="submit">
                            Send
                        </button>
                    </div>
                </div>
            </form>
            <!-- /Edit Message -->
        </div>
    </div>
</template>

<script>
import TheMessage from "./TheMessage";
import TheUsersNav from "./TheUsersNav";
import { NativeEventSource, EventSourcePolyfill } from "event-source-polyfill";
import axios from "axios";

export default {
    name: "Chat",
    components: { TheMessage, TheUsersNav },
    data() {
        return {
            messages: [],
            messageEnCours: "",
            users: {},
            currentUser: null,
            connectedUser: null,
        };
    },
    mounted: function () {
        // Fetch all users and connected user
        var users = axios.get("/get-users").then((response) => {
            this.connectedUser = response.data.connected;
            delete response.data.connected;
            this.currentUser = response.data[0].id;
            this.users = response.data;
        });

        // Subcribe to chat topic with token
        const url = new URL("http://localhost:3000/.well-known/mercure");
        url.searchParams.append("topic", "http://chat.localhost");
        const EventSourcePoly = EventSourcePolyfill;
        const eventSource = new EventSourcePoly(url, {
            headers: {
                Authorization: "Bearer " + token,
            },
        });

        // React to incomings messages
        eventSource.onmessage = (e) => {
            const data = JSON.parse(e.data);
            this.messages.push({
                message: data.message,
                from: data.from,
                to: data.to,
            });
            this.messageEnCours = "";
            setTimeout(() => {
                this.scrollDown();
                500;
            });
        };
    },
    methods: {
        submitMessage: function (e) {
            e.preventDefault();
            if (this.messageEnCours.length == 0) {
                return;
            }
            const postData = JSON.stringify({
                message: this.messageEnCours,
                to: this.currentUser,
            });

            axios
                .post("/publish", postData)
                .then((data) => {
                    console.log(data.status, "Msg sent!");
                })
                .catch((error) => console.log(error));
        },

        setCurrentUser: function (user) {
            this.currentUser = user;
        },

        scrollDown: function () {
            var messages = this.$el.getElementsByClassName("message");
            if (messages.length > 0) {
                const lastMessage =
                    messages[
                        Object.keys(messages)[Object.keys(messages).length - 1]
                    ]; // Last element of an Object
                lastMessage.scrollIntoView({
                    behavior: "smooth",
                    block: "end",
                });
            }
        },
    },
};
</script>

<style scoped>
#Chat {
    height: 100%;
    background-image: url("/background.jpg");
    background-attachment: fixed;
    background-position: unset;
    overflow: hidden;
}

.chat-container {
    display: flex;
    width: 100%;
    height: 100%;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
}

.chat-messages {
    overflow-y: scroll;
}

.chat-messages::-webkit-scrollbar {
    display: none;
    width: 0;
}

@media screen and (min-width: 1500px) {
    .chat-messages {
        width: 50% !important;
    }
}

@media screen and (min-width: 800px) {
    .chat-messages {
        width: 75% !important;
    }
}

form.edit {
    margin-bottom: 1em;
    margin-top: 1em;
    align-self: flex-end;
    margin-right: 5%;
    width: calc(100% - 166px);
    min-width: 390px;
}

form.edit input {
    font-size: 1.5em;
    font-weight: bold;
}
</style>
